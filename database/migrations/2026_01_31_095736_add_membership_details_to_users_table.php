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
        Schema::table('users', function (Blueprint $table) {
            // Membership type: company or individual
            $table->enum('membership_type', ['company', 'individual'])->nullable()->after('membership_id');
            
            // City
            $table->foreignId('city_id')->nullable()->after('membership_type')->constrained('cities')->nullOnDelete();
            
            // Bio/About
            $table->text('bio')->nullable()->after('city_id');
            
            // For individual: personal photo and certificates
            // For company: commercial registration, company files, certificates
            
            // These will be handled via media library collections:
            // - 'personal_photo' (for individual)
            // - 'certificates' (for both)
            // - 'commercial_registration' (for company)
            // - 'company_files' (for company)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn(['membership_type', 'city_id', 'bio']);
        });
    }
};
