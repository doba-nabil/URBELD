<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public $senderName;
    public $messagePreview;
    public $chatUrl;

    public function __construct($senderName, $messagePreview, $chatUrl)
    {
        $this->senderName = $senderName;
        $this->messagePreview = $messagePreview;
        $this->chatUrl = $chatUrl;
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
            ->subject('رسالة جديدة من ' . $this->senderName)
            ->view('emails.new-message', [
                'user' => $notifiable,
                'senderName' => $this->senderName,
                'messagePreview' => $this->messagePreview,
                'chatUrl' => $this->chatUrl,
                'faviconUrl' => $faviconUrl,
                'logoUrl' => $logoUrl,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'message',
            'title' => 'رسالة جديدة',
            'message' => "لديك رسالة جديدة من {$this->senderName}",
            'link' => $this->chatUrl,
        ];
    }
}

