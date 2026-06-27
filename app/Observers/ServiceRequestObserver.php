<?php

namespace App\Observers;

use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use App\Models\User;

class ServiceRequestObserver
{
    /**
     * Handle the ServiceRequest "created" event.
     */
    public function created(ServiceRequest $serviceRequest): void
    {
        // Notify matching providers if the request is created as pending directly.
        if ($serviceRequest->status === ServiceRequest::STATUS_PENDING) {
            $this->notifyMatchingProviders($serviceRequest);
        }

        // Send System-wide notification to Dashboard (shared by all admins)
        \App\Models\Notification::create([
            'user_id' => null, // NULL means it shows in the Admin Notification list/navbar
            'type' => 'request_moderation',
            'title' => __('admin.request_moderation_title'),
            'message' => __('admin.request_moderation_body', [
                'name' => $serviceRequest->user->name ?? __('admin.user'),
                'id' => $serviceRequest->id
            ]),
            'link' => url('/admin-panel/service-requests/' . $serviceRequest->id),
            'data' => [
                'type' => 'request_moderation',
                'service_request_id' => $serviceRequest->id
            ]
        ]);
    }

    /**
     * Handle the ServiceRequest "updated" event.
     */
    public function updated(ServiceRequest $serviceRequest): void
    {
        // Check if the status transitioned from under_review to pending.
        if ($serviceRequest->wasChanged('status') && 
            $serviceRequest->status === ServiceRequest::STATUS_PENDING && 
            $serviceRequest->getOriginal('status') === ServiceRequest::STATUS_UNDER_REVIEW) {
            
            \Log::info("ServiceRequest #{$serviceRequest->id} approved by admin. Triggering notifications to providers.");
            $this->notifyMatchingProviders($serviceRequest);
        }
    }

    /**
     * Broadcast the new request to all matching providers and create response shells.
     */
    protected function notifyMatchingProviders(ServiceRequest $serviceRequest): void
    {
        $categoryId = $serviceRequest->category_id;
        $subCategoryId = $serviceRequest->sub_category_id;
        $matchCategoryIds = array_filter([$categoryId, $subCategoryId]);

        $query = User::serviceProviders()->where('active', 'active');

        $query->where(function ($q) use ($serviceRequest, $matchCategoryIds) {
            // Option A: Matches category and provider_type
            $q->where(function ($sub) use ($serviceRequest, $matchCategoryIds) {
                $sub->whereHas('categories', function ($catQ) use ($matchCategoryIds) {
                    $catQ->whereIn('categories.id', $matchCategoryIds);
                });

                if ($serviceRequest->is_consultation) {
                    $sub->where('provider_type', 'individual');
                } else {
                    $sub->where('provider_type', 'company');
                }
            });

            // Option B: Specifically targeted provider
            if ($serviceRequest->provider_id) {
                $q->orWhere('id', $serviceRequest->provider_id);
            }
        });

        $providers = $query->get();
        \Log::info("Found " . $providers->count() . " matching providers for Request #{$serviceRequest->id}");

        $responses = [];
        $now = now();
        
        foreach ($providers as $provider) {
            if (!\App\Models\ServiceRequestResponse::where('service_request_id', $serviceRequest->id)
                ->where('user_id', $provider->id)->exists()) {
                
                $responses[] = [
                    'service_request_id' => $serviceRequest->id,
                    'user_id' => $provider->id,
                    'status' => 'pending',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($responses)) {
            \App\Models\ServiceRequestResponse::insert($responses);
            \Log::info("Inserted " . count($responses) . " missing response shells for Request #{$serviceRequest->id}");
        }

        if ($providers->isNotEmpty()) {
            // Notify all providers (even if shells were already there)
            \Illuminate\Support\Facades\Notification::send($providers, new \App\Notifications\NewServiceRequestNotification($serviceRequest));
            \Log::info("Sent NewServiceRequestNotification to " . $providers->count() . " providers for Request #{$serviceRequest->id}");
        }
    }

    /**
     * Handle the ServiceRequest "deleted" event.
     */
    public function deleted(ServiceRequest $serviceRequest): void
    {
        //
    }
}
