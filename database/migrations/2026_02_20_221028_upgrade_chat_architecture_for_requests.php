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
        Schema::table('chats', function (Blueprint $table) {
            // Add service_request_id nullable initially to not break existing data
            $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->cascadeOnDelete()->after('uuid');
            
            // Make from_user_id and to_user_id nullable because new architecture uses chat_participants
            $table->unsignedBigInteger('from_user_id')->nullable()->change();
            $table->unsignedBigInteger('to_user_id')->nullable()->change();
        });

        // Create the pivot table for chat participants
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();

            // A user can only be in a chat once
            $table->unique(['chat_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_participants');

        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['service_request_id']);
            $table->dropColumn('service_request_id');
            // Reverting nullable is slightly risky, better leave it or enforce manually
        });
    }
};
