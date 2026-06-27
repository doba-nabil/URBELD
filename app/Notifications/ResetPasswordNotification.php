<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $faviconUrl = config('settings.favicon') ? asset(config('settings.favicon')) : null;
        $logoUrl = config('settings.logo') ? asset(config('settings.logo')) : null;

        return (new MailMessage)
            ->subject(Lang::get('إعادة تعيين كلمة المرور'))
            ->view('emails.reset-password', [
                'url' => $url,
                'user' => $notifiable,
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
                'faviconUrl' => $faviconUrl,
                'logoUrl' => $logoUrl,
            ]);
    }
}

