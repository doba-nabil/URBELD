<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ProfileCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $provider;

    public function __construct(User $provider)
    {
        $this->provider = $provider;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $type = $this->provider->provider_type === 'company' ? 'شركة' : 'فرد';

        return [
            'title' => 'عضوية جديدة أكملت بياناتها',
            'message' => $this->provider->name . ' (' . $type . ') أكمل بيانات ملفه الشخصي ويحتاج مراجعة.',
            'url' => url('/admin-panel/memberships?status=pending'),
            'user_id' => $this->provider->id,
            'type' => 'profile_completed',
        ];
    }
}
