<?php

namespace App\Observers;

use App\Models\ServiceRequestResponse;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class ServiceRequestResponseObserver
{
    /**
     * Handle the ServiceRequestResponse "created" event.
     */
    public function created(ServiceRequestResponse $response): void
    {
        // Send System-wide notification to Dashboard (shared by all admins)
        \App\Models\Notification::create([
            'user_id' => null, // NULL means it shows in the Admin Notification list/navbar
            'type' => 'response_moderation',
            'title' => __('admin.response_moderation_title'),
            'message' => __('admin.response_moderation_body', [
                'name' => $response->provider->name ?? __('admin.user'),
                'id' => $response->id
            ]),
            'link' => url('/admin-panel/service-requests/' . $response->service_request_id),
            'data' => [
                'type' => 'response_moderation',
                'service_request_response_id' => $response->id
            ]
        ]);
    }

    /**
     * Handle the ServiceRequestResponse "updated" event.
     */
    public function updated(ServiceRequestResponse $response): void
    {
        // Notify Seeker when the offer is approved (moved from under_review to pending)
        if ($response->wasChanged('status') && 
            $response->status === ServiceRequestResponse::STATUS_PENDING && 
            $response->getOriginal('status') === ServiceRequestResponse::STATUS_UNDER_REVIEW) {
            
            $serviceRequest = $response->serviceRequest;
            if ($serviceRequest && $serviceRequest->user) {
                $serviceRequest->user->notify(new \App\Notifications\ServiceRequestResponseNotification(
                    $serviceRequest,
                    $response,
                    'new_response'
                ));
            }
        }
    }
}
