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
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->string('quantity')->nullable()->after('title');
            $table->foreignId('sub_category_id')->nullable()->constrained('categories')->nullOnDelete()->after('category_id');
            $table->string('location', 500)->nullable()->after('description');
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('voice_record')->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn(['quantity', 'sub_category_id', 'location', 'latitude', 'longitude', 'voice_record']);
        });
    }
};
