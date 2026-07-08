<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان البانر (للأدمن)
            // ربط بعرض مورد (اختياري - البانر قد يكون مستقلاً)
            $table->foreignId('supplier_offer_id')->nullable()->constrained('supplier_offers')->nullOnDelete();
            // نطاق الظهور
            $table->enum('page_scope', [
                'home',            // الصفحة الرئيسية فقط
                'all_categories',  // كل صفحات التصنيفات
                'specific_category', // تصنيف محدد
                'tenders',         // صفحة المناقصات
                'custom',          // صفحة مخصصة
            ])->default('home');
            // إذا كان specific_category
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            // إذا كان custom
            $table->string('custom_page')->nullable()->comment('Route name or slug of the custom page');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
