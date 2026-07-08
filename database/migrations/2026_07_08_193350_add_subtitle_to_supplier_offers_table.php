<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_offers', function (Blueprint $table) {
            // العنوان الفرعي للعرض (يظهر أسفل العنوان الرئيسي)
            $table->string('subtitle')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('supplier_offers', function (Blueprint $table) {
            $table->dropColumn('subtitle');
        });
    }
};
