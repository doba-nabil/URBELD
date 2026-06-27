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
        Schema::create('user_membership_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('membership_id')->constrained('memberships')->cascadeOnDelete();
            $table->timestamp('started_at'); // تاريخ بداية العضوية
            $table->timestamp('expires_at'); // تاريخ انتهاء العضوية
            $table->decimal('price_paid', 10, 2)->default(0); // السعر المدفوع
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('membership_id');
            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_membership_history');
    }
};
