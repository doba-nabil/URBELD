<?php
namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\ServiceRequest;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('website.profile.edit', [
            'user' => $request->user(),
        ]);
    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
    public function complete(Request $request): View
    {
        if ($request->user()->provider_type === 'supplier') {
            // Get main categories designated for suppliers
            $categories = \App\Models\Category::whereNull('parent_id')
                ->where('supports_supply_requests', true)
                ->with('children')
                ->get();
        } else {
            // Get regular main categories (exclude supplier ones) for service providers
            $categories = \App\Models\Category::whereNull('parent_id')
                ->where('supports_supply_requests', false)
                ->with('children')
                ->get();
        }

        $isSubscriptionEnabled = \App\Models\Setting::getValue('is_subscription_enabled', null, false);
        $packages = [];
        if ($isSubscriptionEnabled) {
            $packages = \App\Models\SubscriptionPackage::active()->ordered()->get();
        }
        return view('website.profile.complete', compact('categories', 'packages', 'isSubscriptionEnabled'));
    }
    public function completeStore(Request $request): RedirectResponse
    {
        $isSubscriptionEnabled = \App\Models\Setting::getValue('is_subscription_enabled', null, false);
        $rules = [
            'bio' => 'nullable|string|max:1000',
            'city_id' => 'nullable|exists:cities,id',
            'years_of_experience' => 'nullable|integer|min:0|max:60',
            'certificate_names.*' => 'nullable|string|max:255',
            'certificates.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
            'commercial_registration' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
            'id_front' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'id_back' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'company_registration_number' => 'nullable|string|max:255',
            'representative_name' => 'nullable|string|max:255',
        ];
        if ($isSubscriptionEnabled) {
            $rules['subscription_package_id'] = 'required|exists:subscription_packages,id';
        }
        $request->validate($rules);
        $user = $request->user();
        $updateData = [
            'bio' => $request->bio,
            'city_id' => $request->city_id,
            'years_of_experience' => $request->years_of_experience,
            'company_registration_number' => $request->company_registration_number,
            'representative_name' => $request->representative_name,
        ];
        if ($isSubscriptionEnabled && $request->subscription_package_id) {
            $package = \App\Models\SubscriptionPackage::find($request->subscription_package_id);
            $updateData['subscription_package_id'] = $package->id;
            $updateData['subscription_start_at'] = now();
            $updateData['subscription_end_at'] = now()->addDays($package->duration_days);
        }
        $user->update($updateData);
        if ($request->has('categories')) {
            $user->categories()->sync($request->categories);
        }
        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $index => $file) {
                $customName = isset($request->certificate_names[$index]) && !empty($request->certificate_names[$index]) ? $request->certificate_names[$index] : $file->getClientOriginalName();
                $user->addMedia($file)->usingName($customName)->toMediaCollection('certificates');
            }
        }
        if ($request->hasFile('commercial_registration')) {
            $user->addMediaFromRequest('commercial_registration')->toMediaCollection('commercial_registration');
        }
        if ($request->hasFile('id_front')) {
            $user->addMediaFromRequest('id_front')->toMediaCollection('id_front'); 
        }
        if ($request->hasFile('id_back')) {
            $user->addMediaFromRequest('id_back')->toMediaCollection('id_back'); 
        }
        // Ensure the user has a membership and link it
        if (!$user->membership_id) {
            $membership = \App\Models\Membership::create([
                'name' => ['ar' => $user->name],
                'type' => $user->provider_type ?? 'individual',
                'is_active' => false, // Will be activated by admin
                'city_id' => $user->city_id,
            ]);
            $user->update(['membership_id' => $membership->id]);
        }
        // Notify admins when a provider completes their profile
        if ($user->user_type === 'service_provider') {
            $admins = \App\Models\User::where('is_admin', true)->get();
            if ($admins->count() > 0) {
                \Illuminate\Support\Facades\Notification::send(
                    $admins,
                    new \App\Notifications\ProfileCompletedNotification($user)
                );
            }
        }
        return redirect()->route('profile.complete')->with('success', __('admin.profile_updated_success'));
    }
    public function requests(Request $request): View
    {
        $user = $request->user();
        if ($user->isServiceProvider()) {
            // For providers, show requests where they have a response (including invitations)
            $requests = ServiceRequest::whereHas('responses', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['category', 'subCategory', 'awardedProvider', 'user'])
            ->with(['responses' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->withCount('responses')
            ->latest()
            ->get();
        } else {
            // For seekers, show requests they created
            $requests = $user->serviceRequests()
                ->with(['category', 'subCategory', 'awardedProvider'])
                ->withCount('responses')
                ->latest()
                ->get();
        }

        // Supply requests created by the user (طلبات التوريد بتاعتهم)
        $supplyRequests = \App\Models\SupplyRequest::with(['city', 'responses'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('website.profile.requests', compact('requests', 'supplyRequests'));
    }
    public function reports(Request $request): View
    {
        $user = $request->user();
        $kpis = [];
        $recentActivity = [];
        if ($user->user_type === 'service_provider') {
            // Provider Reports
            $totalResponses = $user->serviceRequestResponses()->count();
            $completedProjects = $user->serviceRequestResponses()->where('status', \App\Models\ServiceRequestResponse::STATUS_ACCEPTED)
                ->whereHas('serviceRequest', function($q) {
                    $q->whereIn('status', [\App\Models\ServiceRequest::STATUS_COMPLETED, 'work_completed']);
                })->count();
            $totalRevenue = $user->serviceRequestResponses()->where('status', \App\Models\ServiceRequestResponse::STATUS_ACCEPTED)
                ->whereHas('serviceRequest', function($q) {
                    $q->whereIn('status', [\App\Models\ServiceRequest::STATUS_COMPLETED, \App\Models\ServiceRequest::STATUS_SEEKER_CONFIRMED]);
                })->sum('proposed_price');
            $kpis = [
                ['title' => __('admin.total_responses_submitted'), 'value' => $totalResponses, 'icon' => 'fas fa-briefcase', 'color' => 'primary'],
                ['title' => __('admin.completed_projects'), 'value' => $completedProjects, 'icon' => 'fas fa-check', 'color' => 'success'],
                ['title' => __('admin.total_revenue'), 'value' => number_format($totalRevenue, 2) . ' ' . __('admin.currency'), 'icon' => 'fas fa-money-bill', 'color' => 'info'],
                ['title' => __('admin.average_rating'), 'value' => number_format($user->average_rating, 1) . ' / 5', 'icon' => 'fas fa-star', 'color' => 'warning'],
            ];
            // Recent Completed Projects
            $recentActivity = $user->serviceRequestResponses()
                ->where('status', \App\Models\ServiceRequestResponse::STATUS_ACCEPTED)
                ->with(['serviceRequest.category'])
                ->latest()
                ->take(10)
                ->get()
                ->map(function($response) {
                    return [
                        'request_id' => $response->serviceRequest->id,
                        'title'       => $response->serviceRequest->category->name ?? __('admin.service_request'),
                        'date' => $response->created_at->format('Y-m-d'),
                        'status' => __('admin.' . $response->serviceRequest->status),
                        'status_code' => $response->serviceRequest->status,
                        'amount' => number_format($response->proposed_price, 2) . ' ' . __('admin.currency'),
                        'route' => route('requests.show', $response->serviceRequest->id)
                    ];
                });
            // Provider Tender Reports
            $totalTenderResponses = $user->tenderApplications()->count();
            $completedTenderProjects = $user->tenderApplications()->where('status', \App\Models\TenderApplication::STATUS_ACCEPTED)->count();
            $totalTenderRevenue = $user->tenderApplications()->where('status', \App\Models\TenderApplication::STATUS_ACCEPTED)->sum('price');
            $tenderKpis = [
                ['title' => __('website.tenders_submitted') ?? 'عروض مقدمة', 'value' => $totalTenderResponses, 'icon' => 'fas fa-briefcase', 'color' => 'primary'],
                ['title' => __('website.tenders_accepted') ?? 'عروض مقبولة', 'value' => $completedTenderProjects, 'icon' => 'fas fa-check', 'color' => 'success'],
                ['title' => __('website.expected_revenue') ?? 'إيرادات محتملة', 'value' => number_format($totalTenderRevenue, 2) . ' ' . __('admin.currency'), 'icon' => 'fas fa-money-bill', 'color' => 'info'],
            ];
            $recentTenderActivity = $user->tenderApplications()->with('tender')
                ->latest()
                ->take(10)
                ->get()
                ->map(function($app) {
                    return [
                        'request_id' => $app->tender_id,
                        'title' => $app->tender->title ?? 'مناقصة',
                        'date' => $app->created_at->format('Y-m-d'),
                        'status' => __('admin.' . $app->status) ?? $app->status,
                        'status_code' => $app->status,
                        'amount' => number_format($app->price, 2) . ' ' . __('admin.currency'),
                        'route' => route('tenders.show', $app->tender_id)
                    ];
                });
        } else {
            // Seeker Reports
            $totalRequests = $user->serviceRequests()->count();
            $completedRequests = $user->serviceRequests()->whereIn('status', [\App\Models\ServiceRequest::STATUS_COMPLETED, 'work_completed'])->count();
            $totalSpent = $user->serviceRequests()
                ->whereIn('status', [\App\Models\ServiceRequest::STATUS_COMPLETED, \App\Models\ServiceRequest::STATUS_SEEKER_CONFIRMED])
                ->with('acceptedResponse')
                ->get()
                ->sum(function($req) {
                    return $req->acceptedResponse ? $req->acceptedResponse->proposed_price : 0;
                });
            $kpis = [
                ['title' => __('admin.total_requests_created'), 'value' => $totalRequests, 'icon' => 'fas fa-file-alt', 'color' => 'primary'],
                ['title' => __('admin.completed_requests_count'), 'value' => $completedRequests, 'icon' => 'fas fa-check', 'color' => 'success'],
                ['title' => __('admin.total_amount_paid'), 'value' => number_format($totalSpent, 2) . ' ' . __('admin.currency'), 'icon' => 'fas fa-credit-card', 'color' => 'info'],
            ];
            // Recent Requests
            $recentActivity = $user->serviceRequests()
                ->with(['category', 'acceptedResponse'])
                ->latest()
                ->take(10)
                ->get()
                ->map(function($req) {
                    return [
                        'request_id' => $req->id,
                        'title'       => $req->category->name ?? __('admin.service_request'),
                        'date' => $req->created_at->format('Y-m-d'),
                        'status' => __('admin.' . $req->status),
                        'status_code' => $req->status,
                        'amount' => $req->acceptedResponse ? number_format($req->acceptedResponse->proposed_price, 2) . ' ' . __('admin.currency') : __('admin.no_cost_defined'),
                        'route' => route('requests.show', $req->id)
                    ];
                });
            // Seeker Tender Reports
            $totalTenders = $user->tenders()->count();
            $completedTenders = $user->tenders()->where('status', \App\Models\Tender::STATUS_CLOSED)->count();
            $totalTenderSpent = $user->tenders()->where('status', \App\Models\Tender::STATUS_CLOSED)
                ->with('applications')
                ->get()
                ->sum(function($tender) {
                    return $tender->applications->where('status', \App\Models\TenderApplication::STATUS_ACCEPTED)->sum('price');
                });
            $tenderKpis = [
                ['title' => __('website.total_tenders') ?? 'إجمالي المناقصات', 'value' => $totalTenders, 'icon' => 'fas fa-file-alt', 'color' => 'primary'],
                ['title' => __('website.completed_tenders') ?? 'مناقصات مكتملة', 'value' => $completedTenders, 'icon' => 'fas fa-check', 'color' => 'success'],
                ['title' => __('website.total_spent') ?? 'الإنفاق الإجمالي', 'value' => number_format($totalTenderSpent, 2) . ' ' . __('admin.currency'), 'icon' => 'fas fa-credit-card', 'color' => 'info'],
            ];
            $recentTenderActivity = $user->tenders()
                ->with('applications')
                ->latest()
                ->take(10)
                ->get()
                ->map(function($tender) {
                    $acceptedApp = $tender->applications->where('status', \App\Models\TenderApplication::STATUS_ACCEPTED)->first();
                    return [
                        'request_id' => $tender->id,
                        'title' => $tender->title,
                        'date' => $tender->created_at->format('Y-m-d'),
                        'status' => __('admin.' . $tender->status) ?? $tender->status,
                        'status_code' => $tender->status,
                        'amount' => $acceptedApp ? number_format($acceptedApp->price, 2) . ' ' . __('admin.currency') : '-',
                        'route' => route('tenders.show', $tender->id)
                    ];
                });
        }
        return view('website.profile.reports', compact('user', 'kpis', 'recentActivity', 'tenderKpis', 'recentTenderActivity'));
    }
    public function tenders(Request $request): View
    {
        $user = $request->user();
        // Only for providers and suppliers (not normal individuals)
        $incomingTenders = collect();
        if ($user->isServiceProvider() || $user->isSupplier() || $user->isCompanyProvider()) {
            $userCategoryIds = $user->categories()->pluck('categories.id');
            $incomingTenders = \App\Models\Tender::active()
                ->whereIn('category_id', $userCategoryIds)
                ->where('user_id', '!=', $user->id) // Exclude own tenders
                ->with(['category', 'city'])
                ->latest()
                ->paginate(10, ['*'], 'incoming_page');
        }
        // We'll combine them or pass them separately. Passing separately is usually easier for the view.
        $myTenders = $user->tenders()
            ->withCount('applications')
            ->latest()
            ->get();
        $myApplications = $user->tenderApplications()
            ->with(['tender.category'])
            ->latest()
            ->get();
        $savedTenders = $user->savedTenders()
            ->with(['tender.category', 'tender.city'])
            ->latest()
            ->get();
        return view('website.profile.tenders', compact('user', 'incomingTenders', 'myTenders', 'myApplications', 'savedTenders'));
    }
    public function destroyMedia(Request $request, $mediaId): RedirectResponse
    {
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($mediaId);
        if ($media->model_id !== $request->user()->id) {
            abort(403);
        }
        $media->delete();
        return redirect()->back()->with('success', __('admin.file_deleted_success'));
    }
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'personal_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $user = $request->user();
        if ($request->hasFile('personal_photo')) {
            $user->clearMediaCollection('personal_photo');
            $user->clearMediaCollection('users'); // in case it was stored as users collection initially
            $user->addMediaFromRequest('personal_photo')->toMediaCollection('personal_photo');
        }
        return redirect()->back()->with('success', __('admin.photo_updated_success'));
    }
    /**
     * Show the public profile of any user (personal info + certificates only).
     * - supplier membership_type or provider_type => public-profile-supplier
     * - company/institution membership_type or provider_type => public-profile-company
     */
    public function showPublic(\App\Models\User $user)
    {
        $user->load(['categories', 'city']);
        // Get average rating
        $averageRating = \App\Models\Rating::where('rated_id', $user->id)->avg('rating') ?? 0;
        $ratingsCount = \App\Models\Rating::where('rated_id', $user->id)->count();
        // Get certificates
        $certificates = $user->getMedia('certificates');
        // Completed projects count
        $completedProjects = \App\Models\ServiceRequest::where('awarded_provider_id', $user->id)
            ->whereIn('status', [\App\Models\ServiceRequest::STATUS_COMPLETED, 'work_completed'])
            ->count();

        // Determine profile type: membership_type takes priority, fallback to provider_type
        $membershipType = $user->membership_type ?? $user->provider_type;

        if ($membershipType === 'supplier' || $user->provider_type === 'supplier') {
            $user->load('classification');
            return view('website.profile.public-profile-supplier', compact(
                'user',
                'averageRating',
                'ratingsCount',
                'certificates',
                'completedProjects'
            ));
        }

        // company, institution, individual, company (provider_type) all go to company profile
        return view('website.profile.public-profile-company', compact(
            'user',
            'averageRating',
            'ratingsCount',
            'certificates',
            'completedProjects'
        ));
    }
    /**
     * Show only the certificates of a user.
     */
    public function showCertificates(\App\Models\User $user)
    {
        $user->load(['city']);
        $certificates = $user->getMedia('certificates');
        return view('website.profile.certificates', compact('user', 'certificates'));
    }
    /**
     * Show only the services of a user.
     */
    public function showServices(\App\Models\User $user)
    {
        $user->load(['city']);
        $services = $user->services()->active()->ordered()->paginate(12);
        return view('website.profile.services', compact('user', 'services'));
    }
    /**
     * Show only the works (portfolio) of a user.
     */
    public function showWorks(\App\Models\User $user)
    {
        $user->load(['city']);
        $works = $user->works()->orderBy('sort_order')->get();
        return view('website.profile.public-works', compact('user', 'works'));
    }
    public function subscription(\Illuminate\Http\Request $request): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        if (!$request->user()->isServiceProvider()) {
            return redirect()->route('profile.edit');
        }
        $user = $request->user();
        $isSubscriptionEnabled = \App\Models\Setting::getValue('is_subscription_enabled', null, false);
        $packages = \App\Models\SubscriptionPackage::active()->ordered()->get();
        $maxServices = 0;
        $usedServices = 0;
        $servicesPercent = 0;
        $maxWorks = 0;
        $usedWorks = 0;
        $worksPercent = 0;
        
        $maxProducts = 0;
        $usedProducts = 0;
        $productsPercent = 0;
        
        $maxOffers = 0;
        $usedOffers = 0;
        $offersPercent = 0;
        
        $currentFeatures = [];
        if ($user->hasActiveSubscription() && $user->subscriptionPackage) {
            $currentPrice = $user->subscriptionPackage->price;
            $packages = $packages->filter(function($package) use ($currentPrice) {
                return $package->price > $currentPrice;
            });
        }
        
        if ($user->subscriptionPackage) {
            // Service provider limits
            $maxServices = $user->subscriptionPackage->max_services;
            $usedServices = $user->services()->count();
            $servicesPercent = $maxServices > 0 ? min(100, ($usedServices / $maxServices) * 100) : 0;
            $maxWorks = $user->subscriptionPackage->works_limit;
            $usedWorks = $user->works()->count();
            $worksPercent = $maxWorks > 0 ? min(100, ($usedWorks / $maxWorks) * 100) : 0;
            
            // Supplier limits
            $maxProducts = $user->subscriptionPackage->max_products;
            $usedProducts = $user->products()->count();
            $productsPercent = $maxProducts > 0 ? min(100, ($usedProducts / $maxProducts) * 100) : 0;
            $maxOffers = $user->subscriptionPackage->max_offers;
            $usedOffers = $user->supplierOffers()->count();
            $offersPercent = $maxOffers > 0 ? min(100, ($usedOffers / $maxOffers) * 100) : 0;
            
            $featuresData = $user->subscriptionPackage->features;
            $currentFeatures = is_string($featuresData) ? json_decode($featuresData, true) : $featuresData;
            $currentFeatures = is_array($currentFeatures) ? array_filter($currentFeatures) : [];
        }
        return view('website.profile.subscription', compact(
            'user', 'packages', 'isSubscriptionEnabled',
            'maxServices', 'usedServices', 'servicesPercent',
            'maxWorks', 'usedWorks', 'worksPercent',
            'maxProducts', 'usedProducts', 'productsPercent',
            'maxOffers', 'usedOffers', 'offersPercent',
            'currentFeatures'
        ));
    }
}
