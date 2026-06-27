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
        // Update service_requests table
        DB::statement("ALTER TABLE service_requests MODIFY COLUMN status ENUM('under_review', 'pending', 'provider_accepted', 'seeker_confirmed_provider', 'inspection_scheduled', 'inspection_done', 'work_completed', 'completed', 'time_expired', 'cancelled', 'rejected_by_user') NOT NULL DEFAULT 'under_review'");
        
        // Update service_request_responses table
        DB::statement("ALTER TABLE service_request_responses MODIFY COLUMN status ENUM('under_review', 'pending', 'accepted', 'rejected', 'timeout') NOT NULL DEFAULT 'under_review'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert service_requests table
        DB::statement("ALTER TABLE service_requests MODIFY COLUMN status ENUM('pending', 'provider_accepted', 'seeker_confirmed_provider', 'inspection_scheduled', 'inspection_done', 'work_completed', 'completed', 'time_expired', 'cancelled', 'rejected_by_user') NOT NULL DEFAULT 'pending'");
        
        // Revert service_request_responses table
        DB::statement("ALTER TABLE service_request_responses MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'timeout') NOT NULL DEFAULT 'pending'");
    }
};
