<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Change to string first to avoid truncation during data update
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('status')->change();
        });

        // 2. Convert existing data to new statuses
        DB::table('service_requests')->whereIn('status', ['new', 'pending_response'])->update(['status' => 'pending']);
        DB::table('service_requests')->where('status', 'accepted')->update(['status' => 'provider_accepted']);
        DB::table('service_requests')->where('status', 'under_inspection')->update(['status' => 'inspection_scheduled']);
        DB::table('service_requests')->where('status', 'agreed')->update(['status' => 'seeker_confirmed_provider']);
        DB::table('service_requests')->whereIn('status', ['rejected', 'provider_rejected'])->update(['status' => 'cancelled']);

        // 3. Change back to new enum
        Schema::table('service_requests', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'provider_accepted',
                'seeker_confirmed_provider',
                'inspection_scheduled',
                'inspection_done',
                'completed',
                'time_expired',
                'cancelled'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->enum('status', [
                'new',
                'pending_response',
                'accepted',
                'rejected',
                'time_expired',
                'under_inspection',
                'agreed',
                'completed'
            ])->default('new')->change();
        });
    }
};
