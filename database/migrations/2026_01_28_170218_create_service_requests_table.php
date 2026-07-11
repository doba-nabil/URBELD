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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->enum('status', [
                'new',
                'pending_response',
                'accepted',
                'rejected',
                'time_expired',
                'under_inspection',
                'agreed',
                'completed'
            ])->default('new');
            $table->enum('property_type', ['residential', 'commercial', 'industrial', 'other'])->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->text('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('description')->nullable();
            $table->text('blueprint_description')->nullable();
            $table->text('site_photos_description')->nullable();
            $table->foreignId('activity_type_id')->nullable()->constrained('activity_types')->nullOnDelete();
            $table->text('neighbors_description')->nullable();
            $table->timestamp('response_deadline')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
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
