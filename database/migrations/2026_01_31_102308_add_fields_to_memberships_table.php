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
        Schema::table('memberships', function (Blueprint $table) {
            // Remove price, duration_days, features
            $table->dropColumn(['price', 'duration_days', 'features']);
            // Add new fields for individual (engineer)
            $table->string('id_front_image')->nullable()->after('type');
            $table->string('id_back_image')->nullable()->after('id_front_image');
            // Add new fields for company
            $table->string('commercial_registration')->nullable()->after('id_back_image');
            $table->integer('employees_count')->nullable()->after('commercial_registration');
            $table->foreignId('main_category_id')->nullable()->after('employees_count')->constrained('categories')->nullOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('duration_days')->default(30);
            $table->json('features')->nullable();
            $table->dropForeign(['main_category_id']);
            $table->dropColumn([
                'id_front_image',
                'id_back_image',
                'commercial_registration',
                'employees_count',
                'main_category_id'
            ]);
        });
    }
};
