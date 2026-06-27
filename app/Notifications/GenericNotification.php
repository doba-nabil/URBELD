<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification
{
    use Queueable;

    public $title;
    public $messageText;
    public $link;
    public $data;

    public function __construct($title, $messageText, $link = null, $data = [])
    {
        $this->title = $title;
        $this->messageText = $messageText;
        $this->link = $link;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->view('emails.generic', [
                'user' => $notifiable,
                'title' => $this->title,
                'messageText' => $this->messageText,
                'link' => $this->link,
                'logoUrl' => config('settings.logo') ? asset(config('settings.logo')) : null,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'generic',
            'title' => $this->title,
            'message' => $this->messageText,
            'link' => $this->link,
            'data' => $this->data
        ];
    }
}
