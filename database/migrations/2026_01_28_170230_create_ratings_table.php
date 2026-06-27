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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('rater_id')->constrained('users')->cascadeOnDelete(); // من قام بالتقييم
            $table->foreignId('rated_id')->constrained('users')->cascadeOnDelete(); // من تم تقييمه
            $table->tinyInteger('rating')->unsigned(); // من 1 إلى 5
            $table->text('comment')->nullable(); // نص التقييم
            $table->timestamps();
            
            // Indexes
            $table->index('service_request_id');
            $table->index('rater_id');
            $table->index('rated_id');
            
            // منع التقييم المتكرر من نفس المستخدم لنفس الطلب
            $table->unique(['service_request_id', 'rater_id', 'rated_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
