<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Tender;
use App\Models\User;
use App\Notifications\NewTenderNotification;
use Illuminate\Support\Facades\Notification;

class SendTenderNotificationsJob implements ShouldQueue
{
    use Queueable;

    public $tender;

    /**
     * Create a new job instance.
     */
    public function __construct(Tender $tender)
    {
        $this->tender = $tender;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->tender || $this->tender->status !== 'active') {
            return;
        }

        // Get all service providers matching the tender's category
        // Assuming user has a Many-to-Many relation with categories
        User::whereIn('user_type', ['service_provider', 'supplier'])
            ->where('id', '!=', $this->tender->user_id)
            ->whereHas('categories', function ($query) {
                $query->where('categories.id', $this->tender->category_id);
            })
            ->chunkById(100, function ($users) {
                $premiumUsers = collect();
                $freeUsers = collect();

                foreach ($users as $user) {
                    if ($user->subscription_package_id && $user->subscription_end_at && $user->subscription_end_at > now()) {
                        $premiumUsers->push($user);
                    } else {
                        $freeUsers->push($user);
                    }
                }

                if ($premiumUsers->count() > 0) {
                    Notification::send($premiumUsers, new NewTenderNotification($this->tender));
                }

                if ($freeUsers->count() > 0) {
                    $notification = (new NewTenderNotification($this->tender))->delay(now()->addHours(12));
                    Notification::send($freeUsers, $notification);
                }
            });
    }
}
