<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceRequest;

class InspectionScheduledNotification extends Notification
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
            ->subject(__('admin.email_inspection_scheduled_title') ?? 'موعد معاينة جديد')
            ->view('emails.inspection_scheduled', [
                'user' => $notifiable,
                'serviceRequest' => $this->serviceRequest,
                'inspection_date' => $this->serviceRequest->inspection_date,
                'logoUrl' => config('settings.logo') ? asset(config('settings.logo')) : null,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'inspection_scheduled',
            'title' => __('admin.inspection_scheduled_notif_title') ?? 'تم تحديد موعد المعاينة',
            'message' => __('admin.inspection_scheduled_notif_body', [
                'date' => $this->serviceRequest->inspection_date,
                'title' => $this->serviceRequest->category->name ?? ''
            ]) ?? "تم تحديد موعد المعاينة لطلب {$this->serviceRequest->category->name} بتاريخ {$this->serviceRequest->inspection_date}",
            'link' => route('requests.show', $this->serviceRequest->id),
            'data' => [
                'service_request_id' => $this->serviceRequest->id,
                'inspection_date' => $this->serviceRequest->inspection_date
            ]
        ];
    }
}
