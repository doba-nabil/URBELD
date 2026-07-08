<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MembershipDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MembershipRequest;
use App\Models\Membership;
use App\Models\User;
use App\Services\MembershipService;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function __construct(private MembershipService $membershipService) {}

    public function index(MembershipDataTable $dataTable)
    {
        return $dataTable->render('dashboard.memberships.index');
    }

    public function create()
    {
        $categories = \App\Models\Category::whereNull('parent_id')->get();
        $countries = \App\Models\Country::all();
        $packages = \App\Models\SubscriptionPackage::active()->ordered()->get();
        $type = request()->get('type');
        return view('dashboard.memberships.create', compact('categories', 'countries', 'packages', 'type'));
    }

    public function store(MembershipRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';

        // Handle main_category_id based on type
        // For individual, use the value from individual_main_category_id if exists
        // For company, use the value from main_category_id
        if ($data['type'] === 'individual') {
            // Use individual_main_category_id if provided, otherwise use main_category_id
            $data['main_category_id'] = $request->input('main_category_id') ?: null;
        } else {
            // For company, use main_category_id
            $data['main_category_id'] = $request->input('main_category_id') ?: null;
        }

        // Prepare certificates data
        if ($request->has('certificates')) {
            $data['certificates'] = $request->input('certificates', []);
        }

        $data['subscription_package_id'] = $request->input('subscription_package_id');
        $data['subscription_start_at'] = $request->input('subscription_start_at');
        $data['subscription_end_at'] = $request->input('subscription_end_at');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $membership = $this->membershipService->create($data, $request->allFiles());

        return redirect()->route('memberships.index')->with('success', __('admin.save_success'));
    }

    public function show($id)
    {
        $provider = \App\Models\User::where('user_type', 'service_provider')
            ->with(['categories', 'city'])
            ->findOrFail($id);

        return view('dashboard.memberships.show', compact('provider'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'active' => 'required|in:active,pending,blocked',
        ]);

        $provider = \App\Models\User::where('user_type', 'service_provider')->findOrFail($id);
        $oldActive = $provider->active;
        $oldNotes = $provider->admin_notes;
        
        $provider->update([
            'active' => $request->active,
            'admin_notes' => $request->admin_notes,
        ]);

        // Synchronize with membership
        if ($provider->membership_id) {
            $membership = \App\Models\Membership::find($provider->membership_id);
            if ($membership) {
                $membership->update([
                    'is_active' => $request->active === 'active'
                ]);
            }
        }

        // Send notification if status changed or notes changed
        if ($oldActive !== $request->active || $oldNotes !== $request->admin_notes) {
            $provider->notify(new \App\Notifications\MembershipStatusNotification($request->active, $request->admin_notes));
        }

        return redirect()->route('memberships.show', $id)->with('success', __('admin.membership_status_updated'));
    }

    public function edit($id)
    {
        // Try to find by User ID first (since the index list uses user IDs)
        $provider = \App\Models\User::where('user_type', 'service_provider')
            ->with(['categories', 'city.country', 'membership.certificates'])
            ->find($id);
        
        $membership = null;
        if ($provider) {
            $membership = $provider->membership;
        } else {
            // Fallback if ID is actually a membership ID
            $membership = \App\Models\Membership::with(['certificates', 'user.categories', 'user.city.country'])->find($id);
            if ($membership) {
                $provider = $membership->user;
            }
        }
        
        if (!$provider && !$membership) {
            abort(404);
        }

        $categories = \App\Models\Category::whereNull('parent_id')->get();
        $countries = \App\Models\Country::all();
        $cities = $provider->city ? \App\Models\City::where('country_id', $provider->city->country_id)->get() : [];
        $packages = \App\Models\SubscriptionPackage::active()->ordered()->get();
        $classifications = \App\Models\CompanyClassification::all();

        return view('dashboard.memberships.edit', compact('provider', 'membership', 'categories', 'countries', 'cities', 'packages', 'classifications'));
    }

    public function update(MembershipRequest $request, $id)
    {
        $provider = \App\Models\User::where('user_type', 'service_provider')->findOrFail($id);
        $membership = $provider->membership;

        if (!$membership) {
            // Create a default membership if it doesn't exist
            $membership = \App\Models\Membership::create([
                'user_id' => $provider->id,
                'name' => $request->input('name', ['ar' => $provider->name]),
                'type' => $request->input('type', 'individual'),
                'is_active' => true
            ]);
        }

        \Log::info('MembershipController Update Request', ['data' => $request->all()]);
        $data = $request->validated();
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';

        $oldActive = $provider->active;
        $oldType = $provider->provider_type;
        $newActiveStatus = $request->input('active', $provider->active);
        $newType = $request->input('type', $provider->provider_type);
        
        $provider->update([
            'name' => $request->input('name.ar'), 
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'bio' => $request->input('bio'),
            'years_of_experience' => $request->input('years_of_experience'),
            'city_id' => $request->input('city_id'),
            'provider_type' => $newType,
            'membership_id' => $membership->id,
            'subscription_package_id' => $request->input('subscription_package_id'),
            'subscription_start_at' => $request->input('subscription_start_at'),
            'subscription_end_at' => $request->input('subscription_end_at'),
            'active' => $newActiveStatus,
            'is_trusted' => $request->has('is_trusted'),
            'classification_id' => $request->input('classification_id'),
        ]);

        // Send notification if status or type changed
        if ($oldActive !== $newActiveStatus || $oldType !== $newType) {
            $provider->notify(new \App\Notifications\MembershipStatusNotification($newActiveStatus, __('admin.membership_data_updated_by_admin')));
        }

        // Sync membership is_active with user active
        $data['is_active'] = $newActiveStatus === 'active';
        $membership->update(['is_active' => $data['is_active']]);

        if ($request->filled('password')) {
            $provider->update(['password' => bcrypt($request->password)]);
        }

        // Sync categories to User as well (for compatibility with existing show view)
        $allCategoryIds = [];
        if ($request->input('main_category_id')) {
            $allCategoryIds[] = $request->input('main_category_id');
        }
        if ($request->input('sub_categories')) {
            $allCategoryIds = array_unique(array_merge($allCategoryIds, $request->input('sub_categories')));
        }
        $provider->categories()->sync($allCategoryIds);

        // Sync Membership info via Service
        $this->membershipService->update($membership, $data, $request->allFiles(), $provider);

        return redirect()->route('memberships.edit', $id)->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $this->membershipService->delete($id);

            return response()->json([
                'status' => 'success',
                'message' => __('admin.delete_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('admin.delete_error')
            ], 500);
        }
    }

    public function getSubCategories($id)
    {
        $membership = $this->membershipService->getById($id);
        $membership->load('subCategories');

        $subCategories = $membership->subCategories->map(function ($category) {
            $name = $category->name;
            if (is_array($name)) {
                $locale = app()->getLocale();
                $name = $name[$locale] ?? $name['ar'] ?? $name['en'] ?? '-';
            }
            return [
                'id' => $category->id,
                'name' => $name,
                'icon' => $category->icon,
            ];
        });

        $membershipName = $membership->name;
        if (is_array($membershipName)) {
            $locale = app()->getLocale();
            $membershipName = $membershipName[$locale] ?? $membershipName['ar'] ?? $membershipName['en'] ?? '-';
        }

        return response()->json([
            'status' => 'success',
            'data' => $subCategories,
            'membership_name' => $membershipName,
        ]);
    }

    public function getCertificates($id)
    {
        $membership = $this->membershipService->getById($id);
        $membership->load('certificates');

        $certificates = $membership->certificates->map(function ($certificate) {
            $name = $certificate->name;
            if (is_array($name)) {
                $locale = app()->getLocale();
                $name = $name[$locale] ?? $name['ar'] ?? $name['en'] ?? '-';
            }
            return [
                'id' => $certificate->id,
                'name' => $name,
                'image' => $certificate->getFirstMediaUrl('certificate_image'),
            ];
        });

        $membershipName = $membership->name;
        if (is_array($membershipName)) {
            $locale = app()->getLocale();
            $membershipName = $membershipName[$locale] ?? $membershipName['ar'] ?? $membershipName['en'] ?? '-';
        }

        return response()->json([
            'status' => 'success',
            'data' => $certificates,
            'membership_name' => $membershipName,
        ]);
    }

    /**
     * عرض الطلبات المتاحة لمقدم خدمة معين
     */
    public function serviceRequests($id)
    {
        $membership = $this->membershipService->getById($id);
        $membership->load(['mainCategory', 'subCategories']);

        // جمع جميع التصنيفات التي يعمل بها مقدم الخدمة
        $categoryIds = [];

        // إضافة القسم الرئيسي
        if ($membership->main_category_id) {
            $categoryIds[] = $membership->main_category_id;
        }

        // إضافة التصنيفات الفرعية
        $subCategoryIds = $membership->subCategories->pluck('id')->toArray();
        $categoryIds = array_merge($categoryIds, $subCategoryIds);

        // جلب الطلبات التي تنتمي لهذه التصنيفات
        $serviceRequests = \App\Models\ServiceRequest::with(['user', 'category'])
            ->whereIn('category_id', $categoryIds)
            ->whereIn('status', ['new', 'pending_response'])
            ->where(function(\Illuminate\Contracts\Database\Query\Builder $query) {
                $query->whereNull('response_deadline')
                    ->orWhere('response_deadline', '>=', now());
            })
            ->latest()
            ->paginate(15);

        return view('dashboard.memberships.service_requests', compact('membership', 'serviceRequests'));
    }
}
