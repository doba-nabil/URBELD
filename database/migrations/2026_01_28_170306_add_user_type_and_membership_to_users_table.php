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
            $table->enum('user_type', ['service_seeker', 'service_provider'])->nullable()->after('is_admin');
            $table->enum('provider_type', ['individual', 'company'])->nullable()->after('user_type');
            $table->foreignId('membership_id')->nullable()->after('provider_type')->constrained('memberships')->nullOnDelete();
            $table->timestamp('membership_expires_at')->nullable()->after('membership_id');
            $table->string('company_name')->nullable()->after('membership_expires_at');
            $table->string('company_registration_number')->nullable()->after('company_name');
            $table->text('company_description')->nullable()->after('company_registration_number');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['membership_id']);
            $table->dropColumn([
                'user_type',
                'provider_type',
                'membership_id',
                'membership_expires_at',
                'company_name',
                'company_registration_number',
                'company_description'
            ]);
        });
    }
};
