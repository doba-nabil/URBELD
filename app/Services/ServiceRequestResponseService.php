<?php
namespace App\Services;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
class ServiceRequestResponseService
{
    public function getById($id)
    {
        return ServiceRequestResponse::with(['serviceRequest', 'user'])->findOrFail($id);
    }
    public function create(ServiceRequest $serviceRequest, User $user, array $data)
    {
        if (!$serviceRequest->canRespond()) {
            throw new \Exception('لا يمكن الرد على هذا الطلب');
        }
        if ($serviceRequest->responses()->where('user_id', $user->id)->exists()) {
            throw new \Exception('لقد قمت بالرد على هذا الطلب مسبقاً');
        }
        DB::beginTransaction();
        try {
            $data['service_request_id'] = $serviceRequest->id;
            $data['user_id'] = $user->id;
            $data['status'] = ServiceRequestResponse::STATUS_UNDER_REVIEW;
            $data['responded_at'] = now();
            $response = ServiceRequestResponse::create($data);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function accept(ServiceRequestResponse $response)
    {
        DB::beginTransaction();
        try {
            $response->accept();
            $rejectedResponses = $response->serviceRequest->responses()
                ->where('id', '!=', $response->id)
                ->where('status', ServiceRequestResponse::STATUS_PENDING)
                ->get();
            foreach ($rejectedResponses as $rejectedResponse) {
                $rejectedResponse->reject();
                $categoryName = $response->serviceRequest->category->name ?? 'طلب خدمة';
                Notification::create([
                    'user_id' => $rejectedResponse->user->id,
                    'type' => 'service_request_response',
                    'title' => 'تم رفض ردك على الطلب',
                    'message' => "تم رفض ردك على الطلب: {$categoryName}",
                    'link' => route('service-requests.show', $response->serviceRequest->id),
                ]);
            }
            $chatService = app(\App\Services\ChatService::class);
            $chat = $chatService->getOrCreateChat(
                $response->serviceRequest->user,
                $response->user
            );
            $welcomeMessage = "تم قبول عرضك على الطلب. يمكنك الآن التواصل مع طالب الخدمة.";
            $chat->messages()->create([
                'sender_id' => $response->serviceRequest->user->id,
                'message' => $welcomeMessage,
                'is_read' => false,
            ]);
            $senderName = $response->serviceRequest->user->name;
            $chatUrl = route('service-requests.show', $response->serviceRequest->id) . '#chat';
            \App\Models\Notification::create([
                'user_id' => $response->user->id,
                'type' => 'message',
                'title' => 'رسالة جديدة',
                'message' => "لديك رسالة جديدة من {$senderName}",
                'link' => $chatUrl,
            ]);
            $categoryName = $response->serviceRequest->category->name ?? 'طلب خدمة';
            Notification::create([
                'user_id' => $response->user->id,
                'type' => 'service_request_response',
                'title' => 'تم قبول ردك على الطلب',
                'message' => "تم قبول ردك على الطلب: {$categoryName}",
                'link' => route('service-requests.show', $response->serviceRequest->id),
            ]);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function reject(ServiceRequestResponse $response)
    {
        $response->reject();
        return $response;
    }
    public function delete($id)
    {
        $response = ServiceRequestResponse::findOrFail($id);
        return $response->delete();
    }
    public function update(ServiceRequestResponse $response, array $data)
    {
        $response->update($data);
        return $response;
    }
}
