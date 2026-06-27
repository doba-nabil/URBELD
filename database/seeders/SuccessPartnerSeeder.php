<?php

namespace Database\Seeders;

use App\Models\SuccessPartner;
use Illuminate\Database\Seeder;

class SuccessPartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'title' => ['ar' => 'شركة البناء المتقدم', 'en' => 'Advanced Construction Company'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => ['ar' => 'مكتب الاستشارات الهندسية', 'en' => 'Engineering Consulting Office'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => ['ar' => 'شركة البيئة المستدامة', 'en' => 'Sustainable Environment Company'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => ['ar' => 'مؤسسة التصميم المعماري', 'en' => 'Architectural Design Foundation'],
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($partners as $data) {
            // Check if partner exists by Arabic title
            $exists = SuccessPartner::whereJsonContains('title->ar', $data['title']['ar'])->first();
            if (!$exists) {
                SuccessPartner::create($data);
            }
        }
    }
}
