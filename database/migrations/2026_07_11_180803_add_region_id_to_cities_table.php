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
        Schema::table('cities', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            // Make country_id nullable to allow gradual migration
            $table->unsignedBigInteger('country_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
            $table->unsignedBigInteger('country_id')->nullable(false)->change();
        });
    }
};
