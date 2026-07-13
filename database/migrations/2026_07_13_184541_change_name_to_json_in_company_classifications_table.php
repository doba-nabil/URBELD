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
        Schema::table('company_classifications', function (Blueprint $table) {
            $table->json('name_json')->nullable();
        });

        $items = \DB::table('company_classifications')->get();
        foreach ($items as $item) {
            \DB::table('company_classifications')
                ->where('id', $item->id)
                ->update([
                    'name_json' => json_encode(['ar' => $item->name, 'en' => $item->name])
                ]);
        }

        Schema::table('company_classifications', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        
        Schema::table('company_classifications', function (Blueprint $table) {
            $table->renameColumn('name_json', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_classifications', function (Blueprint $table) {
            $table->string('name_string')->nullable();
        });

        $items = \DB::table('company_classifications')->get();
        foreach ($items as $item) {
            $name = json_decode($item->name, true)['ar'] ?? '';
            \DB::table('company_classifications')
                ->where('id', $item->id)
                ->update([
                    'name_string' => $name
                ]);
        }

        Schema::table('company_classifications', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        
        Schema::table('company_classifications', function (Blueprint $table) {
            $table->renameColumn('name_string', 'name');
        });
    }
};
