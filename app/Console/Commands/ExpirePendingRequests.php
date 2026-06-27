<?php

namespace App\Console\Commands;

use App\Models\ServiceRequestResponse;
use Illuminate\Console\Command;

class ExpirePendingRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requests:expire-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire provider pending service requests older than 48 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limitTime = now()->subHours(48);

        // Find pending responses older than 48 hours
        $updatedRows = ServiceRequestResponse::where('status', 'pending')
            ->where('created_at', '<', $limitTime)
            ->update([
                'status' => 'timeout',
                'updated_at' => now(),
            ]);

        $this->info("Expired $updatedRows pending request responses.");
    }
}
