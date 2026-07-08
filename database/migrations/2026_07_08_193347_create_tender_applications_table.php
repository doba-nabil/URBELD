<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 12, 2)->nullable(); // السعر المقدَّم
            $table->unsignedSmallInteger('delivery_days')->nullable(); // مدة التسليم بالأيام
            $table->text('notes')->nullable(); // ملاحظات المتقدم
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            // الملفات تُخزَّن عبر Spatie Media Library (collection: application_files)
            $table->timestamps();

            // مستخدم واحد يقدّم مرة واحدة فقط على نفس المناقصة
            $table->unique(['tender_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_applications');
    }
};
