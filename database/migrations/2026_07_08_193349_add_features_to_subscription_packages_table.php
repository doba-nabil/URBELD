<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            // العضوية المميزة - تعطي البطاقة بوردر مميز في صفحة التصنيف
            $table->boolean('is_featured')->default(false)->after('is_recommended');

            // حدود الموردين
            $table->integer('max_products')->default(0)->after('max_services')
                  ->comment('Max products for supplier (0 = unlimited)');
            $table->integer('max_offers')->default(0)->after('max_products')
                  ->comment('Max offers for supplier (0 = unlimited)');

            // أولوية استلام المناقصات قبل الباقي بـ 12 ساعة
            $table->boolean('receive_tenders_priority')->default(false)->after('max_offers');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->dropColumn([
                'is_featured',
                'max_products',
                'max_offers',
                'receive_tenders_priority',
            ]);
        });
    }
};
