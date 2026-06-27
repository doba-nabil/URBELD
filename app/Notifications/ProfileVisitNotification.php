<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileVisitNotification extends Notification
{
    use Queueable;

    public $visitorName;
    public $visitorProfileUrl;

    public function __construct($visitorName, $visitorProfileUrl)
    {
        $this->visitorName = $visitorName;
        $this->visitorProfileUrl = $visitorProfileUrl;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $faviconUrl = config('settings.favicon') ? asset(config('settings.favicon')) : null;
        $logoUrl = config('settings.logo') ? asset(config('settings.logo')) : null;

        return (new MailMessage)
            ->subject('زيارة جديدة لملفك الشخصي')
            ->view('emails.profile-visit', [
                'user' => $notifiable,
                'visitorName' => $this->visitorName,
                'visitorProfileUrl' => $this->visitorProfileUrl,
                'faviconUrl' => $faviconUrl,
                'logoUrl' => $logoUrl,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'visit',
            'title' => 'زيارة جديدة',
            'message' => "زار ملفك الشخصي: {$this->visitorName}",
            'link' => $this->visitorProfileUrl,
        ];
    }
}

