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
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->json('badge_name')->nullable()->after('name');
        });

        // Migrate current data
        $packages = \Illuminate\Support\Facades\DB::table('subscription_packages')->get();
        foreach ($packages as $pkg) {
            $badgeData = [
                'ar' => $pkg->badge_name_ar,
                'en' => $pkg->badge_name_en
            ];
            \Illuminate\Support\Facades\DB::table('subscription_packages')
                ->where('id', $pkg->id)
                ->update(['badge_name' => json_encode($badgeData)]);
        }

        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->dropColumn(['badge_name_ar', 'badge_name_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->string('badge_name_ar')->nullable()->after('name');
            $table->string('badge_name_en')->nullable()->after('badge_name_ar');
        });

        // Restore data
        $packages = \Illuminate\Support\Facades\DB::table('subscription_packages')->get();
        foreach ($packages as $pkg) {
            $badgeData = json_decode($pkg->badge_name, true);
            \Illuminate\Support\Facades\DB::table('subscription_packages')
                ->where('id', $pkg->id)
                ->update([
                    'badge_name_ar' => $badgeData['ar'] ?? null,
                    'badge_name_en' => $badgeData['en'] ?? null
                ]);
        }

        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->dropColumn('badge_name');
        });
    }
};
