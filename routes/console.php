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
