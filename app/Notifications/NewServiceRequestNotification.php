<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceRequest;

class NewServiceRequestNotification extends Notification
{
    use Queueable;

    public $serviceRequest;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('admin.email_new_service_request_title'))
            ->view('emails.new_service_request', [
                'user' => $notifiable,
                'userName' => $notifiable->name,
                'serviceRequest' => $this->serviceRequest,
                'logoUrl' => config('settings.logo') ? asset(config('settings.logo')) : null,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_service_request',
            'title' => __('admin.new_service_request_title_dash') ?? 'طلب خدمة جديد',
            'message' => __('admin.new_service_request_body_dash', [
                'category' => $this->serviceRequest->category->name ?? ''
            ]) ?? "طلب خدمة جديد في قسم {$this->serviceRequest->category->name}",
            'link' => route('provider.requests.index'),
            'data' => [
                'service_request_id' => $this->serviceRequest->id
            ]
        ];
    }
}
