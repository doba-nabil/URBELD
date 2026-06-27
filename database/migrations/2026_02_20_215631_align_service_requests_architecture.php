<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('service_requests', 'service_type')) {
                $table->enum('service_type', ['contracting', 'engineering', 'environment'])->after('user_id')->nullable();
            }
            if (!Schema::hasColumn('service_requests', 'dynamic_data')) {
                $table->json('dynamic_data')->nullable()->after('description');
            }
            if (!Schema::hasColumn('service_requests', 'awarded_provider_id')) {
                $table->foreignId('awarded_provider_id')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('service_requests', 'inspection_date')) {
                $table->dateTime('inspection_date')->nullable();
            }

            $columnsToDrop = [
                'property_type', 'area', 'blueprint_description', 
                'site_photos_description', 'activity_type_id', 'neighbors_description'
            ];
            $drops = [];
            foreach($columnsToDrop as $col) {
                if (Schema::hasColumn('service_requests', $col)) {
                    $drops[] = $col;
                }
            }
            if(!empty($drops)) {
                if (in_array('activity_type_id', $drops)) {
                    try { $table->dropForeign(['activity_type_id']); } catch(\Exception $e) {}
                }
                $table->dropColumn($drops);
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['awarded_provider_id']);
            $table->dropColumn(['service_type', 'dynamic_data', 'awarded_provider_id', 'inspection_date']);
        });
    }
};
