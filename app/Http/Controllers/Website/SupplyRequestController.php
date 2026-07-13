<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplyRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\SupplyRequest::with(['user', 'city'])->latest();

        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->isServiceProvider()) {
                $categoryIds = $user->categories()->pluck('categories.id')->toArray();
                
                $query->where(function($q) use ($user, $categoryIds) {
                    $q->where('user_id', $user->id)
                      ->orWhere('provider_id', $user->id);
                      
                    if (!empty($categoryIds)) {
                        $q->orWhere(function($subQ) use ($categoryIds) {
                            $subQ->whereNull('provider_id')
                                 ->whereIn('category_id', $categoryIds);
                        });
                    }
                });
            } else {
                $query->where('user_id', $user->id);
            }
        } else {
            $query->whereRaw('1 = 0');
        }

        $requests = $query->paginate(12);

        return view('website.supply_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $supplyRequest = \App\Models\SupplyRequest::with(['user', 'city', 'responses.user'])->findOrFail($id);
        return view('website.supply_requests.show', compact('supplyRequest'));
    }

    public function create(Request $request)
    {
        $regions = \App\Models\Region::with('cities')->orderBy('name')->get();
        $providerId = $request->get('provider_id');
        $providerCities = [];
        $provider = null;
        $providerDoesNotDeliver = false;
        
        if ($providerId) {
            $provider = \App\Models\User::with('deliveryCities', 'city')->find($providerId);
            if ($provider) {
                if ($provider->deliveryCities->isNotEmpty()) {
                    $providerCities = $provider->deliveryCities->pluck('id')->toArray();
                } else {
                    $providerDoesNotDeliver = true;
                }
            }
        }

        return view('website.supply_requests.create', compact('regions', 'providerCities', 'provider', 'providerDoesNotDeliver'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'delivery_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'provider_id' => 'nullable|exists:users,id',
        ]);

        $supplyRequest = new \App\Models\SupplyRequest($validated);
        $supplyRequest->user_id = auth()->id();
        $supplyRequest->category_id = $request->input('category_id');
        $supplyRequest->provider_id = $request->input('provider_id');
        $supplyRequest->status = 'open';
        $supplyRequest->save();

        // Send notifications
        $title = 'طلب توريد جديد';
        $body = 'تم إضافة طلب توريد جديد بعنوان: ' . $supplyRequest->title;

        // Notify Admin
        \App\Services\NotificationService::createAdminNotification(
            'supply_request', 
            $title, 
            $body, 
            route('supply-requests.show', $supplyRequest->id)
        );

        // Notify Provider if specified
        if ($supplyRequest->provider_id) {
            \App\Services\NotificationService::createNotification(
                $supplyRequest->provider_id,
                'supply_request',
                'طلب توريد خاص لك',
                'لديك طلب توريد جديد موجه لك بعنوان: ' . $supplyRequest->title,
                route('website.supply-requests.show', $supplyRequest->id),
                true
            );
        } elseif ($supplyRequest->category_id) {
            // General request to a category: Notify all providers in that category
            $providersInCat = \App\Models\User::whereHas('categories', function($q) use ($supplyRequest) {
                $q->where('categories.id', $supplyRequest->category_id);
            })->where('user_type', 'supplier')->get();
            
            foreach ($providersInCat as $catProvider) {
                \App\Services\NotificationService::createNotification(
                    $catProvider->id,
                    'supply_request',
                    'طلب توريد عام في تخصصك',
                    'يوجد طلب توريد جديد عام في تخصصك بعنوان: ' . $supplyRequest->title,
                    route('website.supply-requests.show', $supplyRequest->id),
                    true
                );
            }
        }

        return redirect()->route('website.supply-requests.index')->with('success', 'تم إضافة طلب التوريد بنجاح');
    }

    public function storeApplication(Request $request, $id)
    {
        $supplyRequest = \App\Models\SupplyRequest::findOrFail($id);
        
        $validated = $request->validate([
            'proposed_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $response = new \App\Models\SupplyRequestResponse($validated);
        $response->user_id = auth()->id();
        $response->supply_request_id = $supplyRequest->id;
        $response->status = 'pending';
        $response->save();

        return redirect()->route('website.supply-requests.show', $supplyRequest->id)->with('success', 'تم تقديم العرض بنجاح');
    }

    public function acceptApplication(Request $request, $id, $applicationId)
    {
        $supplyRequest = \App\Models\SupplyRequest::findOrFail($id);
        
        if ($supplyRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $application = $supplyRequest->responses()->findOrFail($applicationId);

        $supplyRequest->update([
            'awarded_provider_id' => $application->user_id,
            'status' => \App\Models\SupplyRequest::STATUS_IN_PROGRESS,
            'accepted_at' => now(),
        ]);

        return back()->with('success', __('website.offer_accepted_successfully') ?? 'تم قبول العرض بنجاح وتحويل الطلب إلى قيد التنفيذ');
    }

    public function completeWork(Request $request, $id)
    {
        $supplyRequest = \App\Models\SupplyRequest::findOrFail($id);
        
        if ($supplyRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($supplyRequest->status !== \App\Models\SupplyRequest::STATUS_IN_PROGRESS) {
            return back()->with('error', 'الطلب ليس قيد التنفيذ');
        }

        $supplyRequest->update([
            'status' => \App\Models\SupplyRequest::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return back()->with('success', __('website.work_completed_successfully') ?? 'تم تأكيد الانتهاء بنجاح. يرجى تقييم المورد.');
    }
}
