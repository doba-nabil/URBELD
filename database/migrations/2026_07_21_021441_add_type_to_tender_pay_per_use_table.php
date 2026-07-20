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
        Schema::table('tender_pay_per_use', function (Blueprint $table) {
            $table->string('type')->default('apply')->after('tender_id');
            $table->unsignedBigInteger('tender_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_pay_per_use', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedBigInteger('tender_id')->nullable(false)->change();
        });
    }
};
