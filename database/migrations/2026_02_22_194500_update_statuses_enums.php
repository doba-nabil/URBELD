<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update service_requests.status ENUM
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('status')->change();
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'provider_accepted',
                'seeker_confirmed_provider',
                'inspection_scheduled',
                'inspection_done',
                'work_completed',
                'completed',
                'time_expired',
                'cancelled',
                'rejected_by_user'
            ])->default('pending')->change();
        });

        // Update service_request_responses.status ENUM
        Schema::table('service_request_responses', function (Blueprint $table) {
            $table->string('status')->change();
        });

        Schema::table('service_request_responses', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'timeout'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('status')->change();
        });

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

        Schema::table('service_request_responses', function (Blueprint $table) {
            $table->string('status')->change();
        });

        Schema::table('service_request_responses', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected'
            ])->default('pending')->change();
        });
    }
};
