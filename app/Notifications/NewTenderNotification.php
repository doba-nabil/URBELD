<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tender;

class NewTenderNotification extends Notification implements ShouldQueue
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
        return ['database']; // Can add 'mail' later if needed
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_tender',
            'tender_id' => $this->tender->id,
            'title' => 'مناقصة جديدة في تصنيفك',
            'message' => 'تم طرح مناقصة جديدة (' . $this->tender->title . ') تتناسب مع تصنيفك، بادر بتقديم عرضك الآن.',
            'url' => route('website.tenders.show', $this->tender->id),
        ];
    }
}
