<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Get main categories
        $contracting = Category::where('slug', 'contracting')->first();
        $engineering = Category::where('slug', 'engineering-consulting')->first();
        $environment = Category::where('slug', 'environment')->first();

        // Subcategories for Contracting (Already has 4 in other seeder, adding variety)
        if ($contracting) {
            $contractingSubs = [
                ['name' => ['ar' => 'ترميم وصيانة', 'en' => 'Renovation & Maintenance'], 'slug' => 'renovation-maintenance'],
                ['name' => ['ar' => 'تركيبات صحية', 'en' => 'Sanitary Installations'], 'slug' => 'sanitary-installations'],
            ];

            foreach ($contractingSubs as $data) {
                Category::firstOrCreate(
                    ['slug' => $data['slug']],
                    array_merge($data, ['parent_id' => $contracting->id, 'is_active' => true, 'icon' => 'ti tabler-tool'])
                );
            }
        }

        // Subcategories for Engineering Consulting
        if ($engineering) {
            $engineeringSubs = [
                ['name' => ['ar' => 'تصميم داخلي', 'en' => 'Interior Design'], 'slug' => 'interior-design'],
                ['name' => ['ar' => 'تفتيش مباني', 'en' => 'Building Inspection'], 'slug' => 'building-inspection'],
                ['name' => ['ar' => 'إشراف هندسي', 'en' => 'Engineering Supervision'], 'slug' => 'engineering-supervision'],
            ];

            foreach ($engineeringSubs as $data) {
                Category::firstOrCreate(
                    ['slug' => $data['slug']],
                    array_merge($data, ['parent_id' => $engineering->id, 'is_active' => true, 'icon' => 'ti tabler-ruler-2'])
                );
            }
        }

        // Subcategories for Environment
        if ($environment) {
            $environmentSubs = [
                ['name' => ['ar' => 'إدارة نفايات', 'en' => 'Waste Management'], 'slug' => 'waste-management'],
                ['name' => ['ar' => 'استشارات بيئية', 'en' => 'Environmental Consulting'], 'slug' => 'environmental-consulting-sub'],
                ['name' => ['ar' => 'فحص تربة', 'en' => 'Soil Testing'], 'slug' => 'soil-testing'],
            ];

            foreach ($environmentSubs as $data) {
                Category::firstOrCreate(
                    ['slug' => $data['slug']],
                    array_merge($data, ['parent_id' => $environment->id, 'is_active' => true, 'icon' => 'ti tabler-leaf'])
                );
            }
        }
    }
}
