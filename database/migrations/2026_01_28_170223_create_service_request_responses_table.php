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
        Schema::create('service_request_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // مقدم الخدمة
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('message')->nullable(); // رسالة مقدم الخدمة
            $table->decimal('proposed_price', 10, 2)->nullable(); // السعر المقترح
            $table->text('proposed_timeline')->nullable(); // المدة المقترحة
            $table->timestamp('responded_at')->nullable(); // تاريخ الرد
            $table->timestamps();
            
            // Indexes
            $table->index('service_request_id');
            $table->index('user_id');
            $table->index('status');
            
            // منع الرد المتكرر من نفس مقدم الخدمة
            $table->unique(['service_request_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_request_responses');
    }
};
