<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\Category;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        // Get main categories
        $contractingCategory = Category::where('slug', 'contracting')->first();
        $engineeringCategory = Category::where('slug', 'engineering-consulting')->first();
        $environmentCategory = Category::where('slug', 'environment')->first();

        // Individual Memberships (Engineers)
        $individualMemberships = [
            [
                'name' => ['ar' => 'عضوية مهندس ارساء', 'en' => 'ERSAA Engineer Membership'],
                'description' => 'عضوية ارساء للمهندسين الأفراد',
                'type' => 'individual',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => ['ar' => 'عضوية مهندس مميزة', 'en' => 'Premium Engineer Membership'],
                'description' => 'عضوية مميزة للمهندسين الأفراد',
                'type' => 'individual',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        // Company Memberships
        $companyMemberships = [
            [
                'name' => ['ar' => 'عضوية شركة ارساء', 'en' => 'ERSAA Company Membership'],
                'description' => 'عضوية ارساء للشركات',
                'type' => 'company',
                'is_active' => true,
                'sort_order' => 3,
                'main_category_id' => $contractingCategory?->id,
                'employees_count' => 10,
            ],
            [
                'name' => ['ar' => 'عضوية شركة مميزة', 'en' => 'Premium Company Membership'],
                'description' => 'عضوية مميزة للشركات',
                'type' => 'company',
                'is_active' => true,
                'sort_order' => 4,
                'main_category_id' => $engineeringCategory?->id,
                'employees_count' => 50,
            ],
        ];

        foreach ($individualMemberships as $data) {
            Membership::firstOrCreate(
                ['name' => $data['name'], 'type' => 'individual'],
                $data
            );
        }

        foreach ($companyMemberships as $data) {
            $membership = Membership::firstOrCreate(
                ['name' => $data['name'], 'type' => 'company'],
                $data
            );

            // Add subcategories if main category exists
            if ($data['main_category_id'] && $membership->mainCategory) {
                $subCategories = $membership->mainCategory->children()->limit(3)->pluck('id')->toArray();
                if (!empty($subCategories)) {
                    $membership->subCategories()->sync($subCategories);
                }
            }
        }
    }
}
