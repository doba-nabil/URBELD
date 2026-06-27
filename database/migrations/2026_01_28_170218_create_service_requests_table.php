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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // طالب الخدمة
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete(); // القسم
            
            // حالة الطلب
            $table->enum('status', [
                'new',              // جديد
                'pending_response', // بانتظار الرد
                'accepted',         // مقبول
                'rejected',         // مرفوض
                'time_expired',     // منتهي الوقت
                'under_inspection',  // قيد المعاينة
                'agreed',           // تم الاتفاق
                'completed'         // مكتمل
            ])->default('new');
            
            // الحقول المشتركة
            $table->enum('property_type', ['residential', 'commercial', 'industrial', 'other'])->nullable(); // نوع العقار
            $table->decimal('area', 10, 2)->nullable(); // المساحة
            $table->text('location')->nullable(); // العنوان
            $table->string('latitude')->nullable(); // خط العرض
            $table->string('longitude')->nullable(); // خط الطول
            $table->text('description')->nullable(); // وصف الطلب
            
            // حقول خاصة بقسم المقاولات
            $table->text('blueprint_description')->nullable(); // وصف الرسم الكروكي
            
            // حقول خاصة بقسم الاستشارات الهندسية
            $table->text('site_photos_description')->nullable(); // وصف صور الموقع
            
            // حقول خاصة بقسم البيئة
            $table->foreignId('activity_type_id')->nullable()->constrained('activity_types')->nullOnDelete(); // نوع النشاط
            $table->text('neighbors_description')->nullable(); // وصف الجيران من الجهات الأربعة
            
            // معلومات الوقت
            $table->timestamp('response_deadline')->nullable(); // آخر موعد للرد (48 ساعة)
            $table->timestamp('accepted_at')->nullable(); // تاريخ القبول
            $table->timestamp('completed_at')->nullable(); // تاريخ الإكمال
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('response_deadline');
            $table->index('activity_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
