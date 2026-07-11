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
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('show_in_home')->default(true)->after('is_active');
            $table->boolean('supports_tenders')->default(false)->after('show_in_home');
            $table->boolean('supports_supply_requests')->default(false)->after('supports_tenders');
            $table->boolean('is_full_width')->default(false)->after('supports_supply_requests');
            $table->integer('sort_order')->default(0)->after('is_full_width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['show_in_home', 'supports_tenders', 'supports_supply_requests', 'is_full_width', 'sort_order']);
        });
    }
};
