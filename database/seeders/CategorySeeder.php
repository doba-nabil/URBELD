<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => ['ar' => 'قسم المقاولات', 'en' => 'Contracting'],
                'description' => ['ar' => null, 'en' => null],
                'slug' => 'contracting',
                'icon' => 'ti tabler-building-skyscraper',
                'is_active' => true,
            ],
            [
                'name' => ['ar' => 'قسم الاستشارات الهندسية', 'en' => 'Engineering Consulting'],
                'description' => ['ar' => null, 'en' => null],
                'slug' => 'engineering-consulting',
                'icon' => 'ti tabler-compass',
                'is_active' => true,
            ],
            [
                'name' => ['ar' => 'قسم البيئة', 'en' => 'Environment'],
                'description' => ['ar' => null, 'en' => null],
                'slug' => 'environment',
                'icon' => 'ti tabler-leaf',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}

