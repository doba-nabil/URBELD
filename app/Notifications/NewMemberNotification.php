<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMemberNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $type = $this->user->membership_type == 'company' ? 'شركة' : 'مهندس/فرد';
        
        return (new MailMessage)
                    ->subject('تسجيل عضو جديد - ' . config('app.name'))
                    ->greeting('مرحباً ' . $notifiable->name)
                    ->line('تم تسجيل عضو جديد في المنصة.')
                    ->line('الاسم: ' . $this->user->name)
                    ->line('البريد الإلكتروني: ' . $this->user->email)
                    ->line('نوع العضوية: ' . $type)
                    ->action('عرض الملف الشخصي', url('/admin-panel/users/' . $this->user->id))
                    ->line('يرجى مراجعة البيانات واتخاذ الإجراء اللازم.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $type = $this->user->membership_type == 'company' ? 'شركة' : 'فرد';
        return [
            'user_id' => $this->user->id,
            'title' => 'عضو جديد في انتظار المراجعة',
            'message' => 'قام ' . $this->user->name . ' بالتسجيل كـ ' . $type,
            'link' => url('/admin-panel/users/' . $this->user->id),
            'type' => 'new_member'
        ];
    }
}
