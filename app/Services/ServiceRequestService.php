<?php

namespace App\Services;

use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\NewServiceRequestNotification;
use App\Traits\mediaUploader;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceRequestService
{
    use mediaUploader;

    public function getAll()
    {
        return ServiceRequest::with(['user', 'category', 'activityType'])->latest()->get();
    }

    public function getById($id)
    {
        return ServiceRequest::with(['user', 'category', 'activityType', 'responses.user'])->findOrFail($id);
    }

    public function create(array $data, $blueprints = null, $sitePhotos = null)
    {
        DB::beginTransaction();
        try {
            // حساب موعد انتهاء الوقت (84 ساعة)
            $data['response_deadline'] = Carbon::now()->addHours(84);
            $data['status'] = ServiceRequest::STATUS_UNDER_REVIEW;
            
            // التأكد من وجود user_id
            if (!isset($data['user_id']) || !$data['user_id']) {
                throw new \Exception('يجب تحديد طالب الخدمة');
            }

            $serviceRequest = ServiceRequest::create($data);

            // رفع الملفات حسب القسم
            $category = $serviceRequest->category;
            if ($category && $blueprints) {
                // للمقاولات: الرسم الكروكي
                if (is_array($blueprints)) {
                    foreach ($blueprints as $blueprint) {
                        $this->handleImage($serviceRequest, $blueprint, false, 'blueprints');
                    }
                } else {
                    $this->handleImage($serviceRequest, $blueprints, false, 'blueprints');
                }
            }

            if ($category && $sitePhotos) {
                // للاستشارات الهندسية: صور الموقع
                if (is_array($sitePhotos)) {
                    foreach ($sitePhotos as $photo) {
                        $this->handleImage($serviceRequest, $photo, false, 'site_photos');
                    }
                } else {
                    $this->handleImage($serviceRequest, $sitePhotos, false, 'site_photos');
                }
            }

            // تحديث الحالة إلى بانتظار الرد (في الفلو الجديد ستبقى pending حتى يتم الرد)
            // $serviceRequest->update(['status' => ServiceRequest::STATUS_PENDING]); // Already pending

            // $this->notifyProviders($serviceRequest);

            DB::commit();
            return $serviceRequest;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(ServiceRequest $serviceRequest, array $data, $blueprints = null, $sitePhotos = null)
    {
        DB::beginTransaction();
        try {
            $serviceRequest->update($data);

            // تحديث الملفات
            if ($blueprints !== null) {
                $serviceRequest->clearMediaCollection('blueprints');
                if (is_array($blueprints)) {
                    foreach ($blueprints as $blueprint) {
                        $this->handleImage($serviceRequest, $blueprint, false, 'blueprints');
                    }
                } else {
                    $this->handleImage($serviceRequest, $blueprints, false, 'blueprints');
                }
            }

            if ($sitePhotos !== null) {
                $serviceRequest->clearMediaCollection('site_photos');
                if (is_array($sitePhotos)) {
                    foreach ($sitePhotos as $photo) {
                        $this->handleImage($serviceRequest, $photo, false, 'site_photos');
                    }
                } else {
                    $this->handleImage($serviceRequest, $sitePhotos, false, 'site_photos');
                }
            }

            DB::commit();
            return $serviceRequest;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        return $serviceRequest->delete();
    }

    /**
     * تحديث حالة الطلب تلقائياً عند انتهاء الوقت
     */
    public function updateExpiredRequests()
    {
        $expiredRequests = ServiceRequest::expired()
            ->whereIn('status', [
                ServiceRequest::STATUS_PENDING
            ])
            ->get();

        foreach ($expiredRequests as $request) {
            $request->update(['status' => ServiceRequest::STATUS_TIME_EXPIRED]);
        }

        return $expiredRequests->count();
    }

    /**
     * تغيير حالة الطلب
     */
    public function changeStatus(ServiceRequest $serviceRequest, string $status)
    {
        $serviceRequest->update(['status' => $status]);
        return $serviceRequest;
    }

    /**
     * إرسال إشعارات لمقدمي الخدمات عند إنشاء طلب جديد
     */
    protected function notifyProviders(ServiceRequest $serviceRequest)
    {
        // جلب جميع مقدمي الخدمات النشطين مع عضوية نشطة
        $providers = User::serviceProviders()
            ->withActiveMembership()
            ->where('active', true)
            ->get();

        foreach ($providers as $provider) {
            try {
                $provider->notify(new NewServiceRequestNotification($serviceRequest));
            } catch (\Exception $e) {
                \Log::error('Failed to send notification to provider: ' . $e->getMessage());
            }
        }
    }
}
