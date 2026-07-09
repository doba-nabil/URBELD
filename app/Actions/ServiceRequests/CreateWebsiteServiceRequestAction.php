<?php

namespace App\Actions\ServiceRequests;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateWebsiteServiceRequestAction
{
    /**
     * Execute the action to create a service request from the website.
     *
     * @param array $data
     * @param Request $request
     * @param int $userId
     * @return ServiceRequest
     * @throws \Exception
     */
    public function execute(array $data, Request $request, int $userId): ServiceRequest
    {
        DB::beginTransaction();

        try {
            $isConsultation = $request->input('is_consultation', false);
            $deadlineHours = $isConsultation ? 2 : 48;

            $serviceRequest = ServiceRequest::create([
                'user_id' => $userId,
                'provider_id' => $data['provider_id'] ?? null,
                'category_id' => $data['category_id'],
                'sub_category_id' => $data['sub_category_id'],
                'location' => $data['location'],
                'city_id' => $data['city_id'],
                'neighborhood' => $data['neighborhood'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'description' => $data['description'],
                'dynamic_data' => $data['dynamic_data'] ?? [],
                'service_id' => $data['service_id'] ?? null,
                'status' => ServiceRequest::STATUS_UNDER_REVIEW,
                'is_consultation' => $isConsultation,
                'response_deadline' => now()->addHours($deadlineHours),
            ]);

            if ($request->hasFile('voice_record')) {
                $path = $request->file('voice_record')->store('voice_records', 'public');
                $serviceRequest->update(['voice_record' => $path]);
            }

            // General Dropzone Attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $mime = $file->getMimeType();
                    $collection = str_starts_with($mime, 'image/') ? 'site_photos' : 'blueprints';
                    $serviceRequest->addMedia($file)->toMediaCollection($collection);
                }
            }

            // Legacy individual inputs just in case
            if ($request->hasFile('blueprints')) {
                foreach ($request->file('blueprints') as $file) {
                    $serviceRequest->addMedia($file)->toMediaCollection('blueprints');
                }
            }

            if ($request->hasFile('site_photos')) {
                foreach ($request->file('site_photos') as $photo) {
                    $serviceRequest->addMedia($photo)->toMediaCollection('site_photos');
                }
            }

            DB::commit();

            // Notify admins
            \App\Services\NotificationService::createAdminNotification(
                'new_request',
                'طلب خدمة جديد',
                "تم إنشاء طلب خدمة جديد برقم #" . $serviceRequest->id,
                route('service-requests.show', $serviceRequest->id)
            );

            return $serviceRequest;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
