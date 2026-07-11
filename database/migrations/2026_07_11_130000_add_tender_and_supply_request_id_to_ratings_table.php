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
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('service_request_id')->nullable()->change();
            $table->foreignId('tender_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supply_request_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('service_request_id')->nullable(false)->change();
            $table->dropForeign(['tender_id']);
            $table->dropForeign(['supply_request_id']);
            $table->dropColumn(['tender_id', 'supply_request_id']);
        });
    }
};
