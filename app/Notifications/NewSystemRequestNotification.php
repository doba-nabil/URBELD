<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceRequest;

class NewSystemRequestNotification extends Notification
{
    use Queueable;

    public $serviceRequest;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function via($notifiable)
    {
        return ['database']; // System notifications are usually dashboard only
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_system_request',
            'title' => __('admin.new_system_request_notif_title') ?? 'طلب جديد يحتاج مراجعة',
            'message' => __('admin.new_system_request_notif_body', ['id' => $this->serviceRequest->id]) ?? "تم إنشاء طلب جديد رقم #{$this->serviceRequest->id} وهو بانتظار مراجعة الإدارة.",
            'link' => url('/admin-panel/service-requests/' . $this->serviceRequest->id),
            'data' => [
                'service_request_id' => $this->serviceRequest->id
            ]
        ];
    }
}
