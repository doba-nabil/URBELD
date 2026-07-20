<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tender;

class NewTenderAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tender;

    /**
     * Create a new notification instance.
     */
    public function __construct(Tender $tender)
    {
        $this->tender = $tender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_tender_admin',
            'tender_id' => $this->tender->id,
            'title' => 'مناقصة جديدة بانتظار المراجعة',
            'message' => 'تمت إضافة مناقصة جديدة بعنوان: ' . $this->tender->title . ' وهي بانتظار موافقتك.',
            'link' => url('/admin-panel/tenders/' . $this->tender->id), // Adjust URL based on your admin panel routes
        ];
    }
}
