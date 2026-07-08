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
        // Update ENUM columns to include 'supplier'
        DB::statement("ALTER TABLE users MODIFY COLUMN membership_type ENUM('company', 'individual', 'supplier') NULL");
        DB::statement("ALTER TABLE users MODIFY COLUMN provider_type ENUM('company', 'individual', 'supplier') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert ENUM columns (Warning: will cause issues if 'supplier' data exists)
        DB::statement("ALTER TABLE users MODIFY COLUMN membership_type ENUM('company', 'individual') NULL");
        DB::statement("ALTER TABLE users MODIFY COLUMN provider_type ENUM('company', 'individual') NULL");
    }
};
