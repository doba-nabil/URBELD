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
        Schema::table('categories', function (Blueprint $table) {
            $table->json('bulk_request_title')->nullable()->after('is_full_width');
            $table->json('bulk_request_subtitle')->nullable()->after('bulk_request_title');
            $table->json('bulk_request_button_text')->nullable()->after('bulk_request_subtitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'bulk_request_title',
                'bulk_request_subtitle',
                'bulk_request_button_text'
            ]);
        });
    }
};
