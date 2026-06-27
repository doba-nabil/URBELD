<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipStatusNotification extends Notification
{

    public $status;
    public $notes;

    /**
     * Create a new notification instance.
     */
    public function __construct($status, $notes = null)
    {
        $this->status = $status;
        $this->notes = $notes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (data_get($notifiable, 'receive_email_notifications', true)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->getStatusText();
        $message_text = 'تم تحديث حالة عضويتك في منصة أوربلد.';
        $subject = 'تحديث حالة العضوية - أوربلد';
        
        return (new MailMessage)
                    ->subject($subject)
                    ->view('emails.membership-status', [
                        'user' => $notifiable,
                        'message_text' => $message_text,
                        'notes' => $this->notes,
                        'status' => $this->status,
                        'statusText' => $statusText,
                        'header_title' => $subject,
                        'header_subtitle' => config('app.name'),
                        'button_text' => 'الانتقال إلى الحساب',
                        'button_link' => route('login'),
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $statusText = $this->getStatusText();
        $body = 'تم تحديث حالة عضويتك إلى (' . $statusText . ')';
        if ($this->notes) {
            $body .= ' - ملاحظات: ' . $this->notes;
        }
        
        return [
            'type' => 'membership_status',
            'title' => 'تحديث حالة العضوية',
            'body' => $body,
            'status' => $this->status,
            'notes' => $this->notes,
            'url' => route('profile.edit'),
        ];
    }

    protected function getStatusText()
    {
        switch ($this->status) {
            case 'active':
                return 'تم قبول العضوية (نشط)';
            case 'pending':
                return 'بانتظار المراجعة (معلق)';
            case 'blocked':
                return 'تم رفض العضوية (محظور)';
            default:
                return $this->status;
        }
    }
}
