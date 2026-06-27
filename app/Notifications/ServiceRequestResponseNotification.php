<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;

class ServiceRequestResponseNotification extends Notification
{
    use Queueable;

    public $serviceRequest;
    public $response;
    public $type; // 'new_response', 'accepted', 'rejected'

    public function __construct(ServiceRequest $serviceRequest, ServiceRequestResponse $response, $type = 'new_response')
    {
        $this->serviceRequest = $serviceRequest;
        $this->response = $response;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = match($this->type) {
            'new_response' => __('admin.email_new_response_title') ?? 'رد جديد على طلبك',
            'accepted' => __('admin.email_response_accepted_title') ?? 'تم قبول عرضك',
            'rejected' => __('admin.email_response_rejected_title') ?? 'تحديث بشأن عرضك',
            default => 'تعديل على طلب الخدمة',
        };

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.service-request-response', [
                'user' => $notifiable,
                'serviceRequest' => $this->serviceRequest,
                'response' => $this->response,
                'type' => $this->type,
                'subject' => $subject,
                'requestUrl' => route('requests.show', $this->serviceRequest->id),
                'logoUrl' => config('settings.logo') ? asset(config('settings.logo')) : null,
            ]);
    }

    public function toArray($notifiable)
    {
        $title = match($this->type) {
            'new_response' => __('admin.new_response_notif_title') ?? 'عرض جديد',
            'accepted' => __('admin.response_accepted_notif_title') ?? 'تم قبول عرضك',
            'rejected' => __('admin.response_rejected_notif_title') ?? 'تم رفض عرضك',
            default => 'تحديث الطلب',
        };

        $message = match($this->type) {
            'new_response' => __('admin.new_response_notif_body', ['provider' => $this->response->user->name ?? '']) ?? "لديك عرض جديد على طلبك من {$this->response->user->name}",
            'accepted' => __('admin.response_accepted_notif_body', ['title' => $this->serviceRequest->category->name ?? '']) ?? "تهانينا! تم قبول عرضك لطلب {$this->serviceRequest->category->name}",
            'rejected' => __('admin.response_rejected_notif_body', ['title' => $this->serviceRequest->category->name ?? '']) ?? "تم اختيار عرض آخر لطلب {$this->serviceRequest->category->name}",
            default => 'حدث تغيير في حالة طلبك',
        };

        return [
            'type' => 'service_request_response',
            'notification_type' => $this->type,
            'title' => $title,
            'message' => $message,
            'link' => route('requests.show', $this->serviceRequest->id),
            'data' => [
                'service_request_id' => $this->serviceRequest->id,
                'response_id' => $this->response->id
            ]
        ];
    }
}
