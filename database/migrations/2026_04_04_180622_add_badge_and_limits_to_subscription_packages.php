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
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->string('badge_name_ar')->nullable()->after('name');
            $table->string('badge_name_en')->nullable()->after('badge_name_ar');
            $table->integer('works_limit')->default(0)->after('max_services')->comment('Max number of portfolio items allowed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->dropColumn(['badge_name_ar', 'badge_name_en', 'works_limit']);
        });
    }
};
