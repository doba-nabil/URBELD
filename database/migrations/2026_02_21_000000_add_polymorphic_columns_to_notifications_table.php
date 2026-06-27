<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Add polymorphic columns required by Laravel's Notifiable trait
            $table->string('notifiable_type')->nullable()->after('id');
            $table->unsignedBigInteger('notifiable_id')->nullable()->after('notifiable_type');
            
            // Add standard data column (JSON) for standard notifications
            $table->json('data')->nullable()->after('message');
            
            // Rename is_read to be more standard (optional but helps compatibility)
            // Laravel usually expects no is_read, just read_at
            // We'll keep is_read for now as the custom service uses it.
        });

        // Sync existing data: Set notifiable_id to user_id and type to App\Models\User
        DB::table('notifications')->update([
            'notifiable_id' => DB::raw('user_id'),
            'notifiable_type' => 'App\\Models\\User',
            // Pre-populate data with existing custom message/title if needed
            // But usually database driver expects JSON in data column
        ]);

        // Standardize: Notifications without user_id are problematic in custom schema
        // but Notifiable usually needs these indexed
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_type_notifiable_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_type_notifiable_id_index');
            $table->dropColumn(['notifiable_type', 'notifiable_id', 'data']);
        });
    }
};
