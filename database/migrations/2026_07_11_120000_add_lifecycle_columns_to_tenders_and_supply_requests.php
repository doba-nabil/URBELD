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
        Schema::table('tenders', function (Blueprint $table) {
            $table->foreignId('awarded_provider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
        });

        Schema::table('supply_requests', function (Blueprint $table) {
            $table->foreignId('awarded_provider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropForeign(['awarded_provider_id']);
            $table->dropColumn(['awarded_provider_id', 'accepted_at', 'completed_at']);
        });

        Schema::table('supply_requests', function (Blueprint $table) {
            $table->dropForeign(['awarded_provider_id']);
            $table->dropColumn(['awarded_provider_id', 'accepted_at', 'completed_at']);
        });
    }
};
