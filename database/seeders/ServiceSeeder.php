<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => ['ar' => 'خدمة الاستشارات الهندسية', 'en' => 'Engineering Consulting Service'],
                'icon' => 'ti tabler-compass',
                'icon_title' => ['ar' => 'استشارات هندسية', 'en' => 'Engineering Consulting'],
                'content' => 'نوفر أفضل الاستشارات الهندسية من قبل خبراء معتمدين',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => ['ar' => 'خدمة المقاولات', 'en' => 'Contracting Service'],
                'icon' => 'ti tabler-building',
                'icon_title' => ['ar' => 'مقاولات', 'en' => 'Contracting'],
                'content' => 'خدمات مقاولات شاملة لجميع أنواع المشاريع',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => ['ar' => 'خدمة الدراسات البيئية', 'en' => 'Environmental Studies Service'],
                'icon' => 'ti tabler-leaf',
                'icon_title' => ['ar' => 'دراسات بيئية', 'en' => 'Environmental Studies'],
                'content' => 'دراسات بيئية شاملة وتقييم الأثر البيئي',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => ['ar' => 'خدمة التصميم المعماري', 'en' => 'Architectural Design Service'],
                'icon' => 'ti tabler-building-skyscraper',
                'icon_title' => ['ar' => 'تصميم معماري', 'en' => 'Architectural Design'],
                'content' => 'تصاميم معمارية مبتكرة وعصرية',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $data) {
            Service::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }
}
