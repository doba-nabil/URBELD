<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('supplier_offer_id')->nullable()->constrained('supplier_offers')->nullOnDelete();
            $table->enum('page_scope', [
                'home',
                'all_categories',
                'specific_category',
                'tenders',
                'custom',
            ])->default('home');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('custom_page')->nullable()->comment('Route name or slug of the custom page');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
