<?php
use App\Jobs\UpdateExpiredServiceRequests;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::job(new UpdateExpiredServiceRequests)->hourly();
// Expire pending responses after 48 hours for providers
Schedule::command('requests:expire-pending')->hourly();

// Process the queue jobs automatically for cPanel deployments
Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping();

// Test to verify Cron is working (Logs to storage/logs/laravel.log)
Schedule::call(function () {
    \Illuminate\Support\Facades\Log::info('Cron Job is working successfully at ' . now()->toDateTimeString());
})->everyMinute();
