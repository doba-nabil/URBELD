<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FavoriteNotification extends Notification
{
    use Queueable;

    public $favoriterName;
    public $profileUrl;

    public function __construct($favoriterName, $profileUrl)
    {
        $this->favoriterName = $favoriterName;
        $this->profileUrl = $profileUrl;
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
            ->subject('أضافك ' . $this->favoriterName . ' إلى المفضلة')
            ->view('emails.favorite', [
                'user' => $notifiable,
                'favoriterName' => $this->favoriterName,
                'profileUrl' => $this->profileUrl,
                'faviconUrl' => $faviconUrl,
                'logoUrl' => $logoUrl,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'favorite',
            'title' => 'إضافة للمفضلة',
            'message' => "أضافك {$this->favoriterName} إلى المفضلة",
            'link' => $this->profileUrl,
        ];
    }
}

