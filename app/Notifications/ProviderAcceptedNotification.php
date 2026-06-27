<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\ServiceRequestResponse;

class ProviderAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $response;

    public function __construct(ServiceRequestResponse $response)
    {
        $this->response = $response;
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (data_get($notifiable, 'receive_email_notifications', true)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable)
    {
        $email = $notifiable->email ?? $notifiable->routeNotificationFor('mail');
        return (new \App\Mail\ProviderAcceptedMail($this->response, $notifiable))
                    ->to($email);
    }

    public function toDatabase($notifiable)
    {
        return [
            'service_request_id' => $this->response->service_request_id,
            'title' => 'تم قبول عرضك!',
            'body' => 'لقد وافق العميل على عرضك للطلب رقم #' . $this->response->service_request_id,
            'url' => route('provider.requests.index'),
        ];
    }
}
