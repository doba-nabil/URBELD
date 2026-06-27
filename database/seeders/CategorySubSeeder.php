<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySubSeeder extends Seeder
{
    public function run(): void
    {
        // Get main categories
        $contracting = Category::where('slug', 'contracting')->first();
        $engineering = Category::where('slug', 'engineering-consulting')->first();
        $environment = Category::where('slug', 'environment')->first();

        // Subcategories for Contracting
        if ($contracting) {
            $contractingSubs = [
                [
                    'name' => ['ar' => 'مقاولات عامة', 'en' => 'General Contracting'],
                    'slug' => 'general-contracting',
                    'icon' => 'ti tabler-tools',
                    'is_active' => true,
                ],
                [
                    'name' => ['ar' => 'مقاولات كهرباء', 'en' => 'Electrical Contracting'],
                    'slug' => 'electrical-contracting',
                    'icon' => 'ti tabler-bolt',
                    'is_active' => true,
                ],
                [
                    'name' => ['ar' => 'مقاولات سباكة', 'en' => 'Plumbing Contracting'],
                    'slug' => 'plumbing-contracting',
                    'icon' => 'ti tabler-droplet',
                    'is_active' => true,
                ],
                [
                    'name' => ['ar' => 'مقاولات دهانات', 'en' => 'Painting Contracting'],
                    'slug' => 'painting-contracting',
                    'icon' => 'ti tabler-paint',
                    'is_active' => true,
                ],
            ];

            foreach ($contractingSubs as $data) {
                Category::firstOrCreate(
                    ['slug' => $data['slug']],
                    array_merge($data, ['parent_id' => $contracting->id])
                );
            }
        }

        // Subcategories for Engineering Consulting
        if ($engineering) {
            $engineeringSubs = [
                [
                    'name' => ['ar' => 'استشارات معمارية', 'en' => 'Architectural Consulting'],
                    'slug' => 'architectural-consulting',
                    'icon' => 'ti tabler-building',
                    'is_active' => true,
                ],
                [
                    'name' => ['ar' => 'استشارات إنشائية', 'en' => 'Structural Consulting'],
                    'slug' => 'structural-consulting',
                    'icon' => 'ti tabler-building-bridge',
                    'is_active' => true,
                ],
                [
                    'name' => ['ar' => 'استشارات ميكانيكية', 'en' => 'Mechanical Consulting'],
                    'slug' => 'mechanical-consulting',
                    'icon' => 'ti tabler-settings',
                    'is_active' => true,
                ],
            ];

            foreach ($engineeringSubs as $data) {
                Category::firstOrCreate(
                    ['slug' => $data['slug']],
                    array_merge($data, ['parent_id' => $engineering->id])
                );
            }
        }

        // Subcategories for Environment
        if ($environment) {
            $environmentSubs = [
                [
                    'name' => ['ar' => 'دراسات بيئية', 'en' => 'Environmental Studies'],
                    'slug' => 'environmental-studies',
                    'icon' => 'ti tabler-leaf',
                    'is_active' => true,
                ],
                [
                    'name' => ['ar' => 'تقييم الأثر البيئي', 'en' => 'Environmental Impact Assessment'],
                    'slug' => 'environmental-impact-assessment',
                    'icon' => 'ti tabler-plant',
                    'is_active' => true,
                ],
            ];

            foreach ($environmentSubs as $data) {
                Category::firstOrCreate(
                    ['slug' => $data['slug']],
                    array_merge($data, ['parent_id' => $environment->id])
                );
            }
        }
    }
}
