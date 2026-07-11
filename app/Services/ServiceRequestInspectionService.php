<?php
namespace App\Services;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestInspection;
use App\Models\ServiceRequestResponse;
use Illuminate\Support\Facades\DB;
class ServiceRequestInspectionService
{
    public function getById($id)
    {
        return ServiceRequestInspection::with(['serviceRequest', 'response'])->findOrFail($id);
    }
    public function schedule(ServiceRequest $serviceRequest, ServiceRequestResponse $response, array $data)
    {
        if ($serviceRequest->status !== ServiceRequest::STATUS_ACCEPTED) {
            throw new \Exception('يجب أن يكون الطلب في حالة مقبول لجدولة المعاينة');
        }
        if ($response->status !== ServiceRequestResponse::STATUS_ACCEPTED) {
            throw new \Exception('يجب أن يكون الرد مقبولاً لجدولة المعاينة');
        }
        DB::beginTransaction();
        try {
            $data['service_request_id'] = $serviceRequest->id;
            $data['response_id'] = $response->id;
            $data['status'] = ServiceRequestInspection::STATUS_SCHEDULED;
            $inspection = ServiceRequestInspection::create($data);
            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_UNDER_INSPECTION,
            ]);
            DB::commit();
            return $inspection;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function complete(ServiceRequestInspection $inspection, $notes = null)
    {
        DB::beginTransaction();
        try {
            $inspection->complete($notes);
            DB::commit();
            return $inspection;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function cancel(ServiceRequestInspection $inspection)
    {
        $inspection->cancel();
        return $inspection;
    }
    public function update(ServiceRequestInspection $inspection, array $data)
    {
        $inspection->update($data);
        return $inspection;
    }
    public function delete($id)
    {
        $inspection = ServiceRequestInspection::findOrFail($id);
        return $inspection->delete();
    }
}
