<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesTableSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'title' => [
                'ar' => 'من نحن',
            ],
            'content' => [
                'ar' => '<p>هذا هو المحتوى العربي لصفحة من نحن.</p>',
            ],
            'slug' => 'about-us'
        ]);
    }
}
