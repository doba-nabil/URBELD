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
            $table->foreignId('subscription_package_id')->nullable()->after('membership_id')->constrained('subscription_packages')->nullOnDelete();
            $table->timestamp('subscription_start_at')->nullable()->after('subscription_package_id');
            $table->timestamp('subscription_end_at')->nullable()->after('subscription_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['subscription_package_id']);
            $table->dropColumn(['subscription_package_id', 'subscription_start_at', 'subscription_end_at']);
        });
    }
};
