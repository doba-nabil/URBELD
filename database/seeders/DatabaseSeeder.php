<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web'], ['display_name' => ['en' => 'Admin', 'ar' => 'مدير النظام']]);
        $providerRole = Role::firstOrCreate(['name' => 'Service Provider', 'guard_name' => 'web'], ['display_name' => ['en' => 'Service Provider', 'ar' => 'مقدم خدمة']]);
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web'], ['display_name' => ['en' => 'User', 'ar' => 'مستخدم / عميل']]);

        // 2. Categories
        $contractingCategory = Category::firstOrCreate(['name' => 'المقاولات العظمى', 'slug' => 'contracting'], ['is_active' => true]);
        $engineeringCategory = Category::firstOrCreate(['name' => 'الاستشارات الهندسية', 'slug' => 'engineering'], ['is_active' => true]);
        $environmentCategory = Category::firstOrCreate(['name' => 'التأهيل البيئي', 'slug' => 'environment'], ['is_active' => true]);

        // 3. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@urbeld.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('password'),
                'phone' => '0500000000',
                'id_number' => '1000000000',
                'active' => 1,
                'user_type' => 'service_seeker' // or null depending on how admin is handled
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // 4. Regular Users (Seekers)
        $seekers = [];
        for ($i = 1; $i <= 3; $i++) {
            $seeker = User::firstOrCreate(
                ['email' => "seeker{$i}@urbeld.com"],
                [
                    'name' => "عميل {$i}",
                    'password' => Hash::make('password'),
                    'phone' => "055000000{$i}",
                    'id_number' => "200000000{$i}",
                    'active' => 1,
                    'user_type' => 'service_seeker'
                ]
            );
            $seeker->roles()->syncWithoutDetaching([$userRole->id]);
            $seekers[] = $seeker;
        }

        // 5. Providers (Contracting, Engineering, Environment)
        $providerData = [
            ['email' => 'contracting@urbeld.com', 'name' => 'شركة المقاولات المتحدة', 'category' => $contractingCategory],
            ['email' => 'engineering@urbeld.com', 'name' => 'مكتب الهندسة الحديث', 'category' => $engineeringCategory],
            ['email' => 'environment@urbeld.com', 'name' => 'مؤسسة البيئة الخضراء', 'category' => $environmentCategory],
        ];

        $providers = [];
        foreach ($providerData as $index => $data) {
            $provider = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'phone' => "056000000{$index}",
                    'id_number' => "300000000{$index}",
                    'active' => 1,
                    'user_type' => 'service_provider',
                    'membership_type' => 'company'
                ]
            );
            $provider->roles()->syncWithoutDetaching([$providerRole->id]);
            // Attach categories via user_categories pivot. Usually accomplished via categories() relation.
            $provider->categories()->syncWithoutDetaching([$data['category']->id]);
            $providers[] = $provider;
        }

        // 6. Service Requests and Responses
        // Request 1: Contracting Request by Seeker 1
        $req1 = ServiceRequest::create([
            'user_id' => $seekers[0]->id,
            'category_id' => $contractingCategory->id,
            'service_type' => 'contracting',
            'location' => 'الرياض',
            'description' => 'أرغب في بناء عمارة سكنية في الرياض بمساحة 500 متر مربع.',
            'status' => 'pending',
            'response_deadline' => now()->addHours(48),
            'dynamic_data' => ['area' => '500', 'floors' => '3']
        ]);
        // Provider 1 (Contracting) leaves a response
        ServiceRequestResponse::firstOrCreate(
            ['service_request_id' => $req1->id, 'user_id' => $providers[0]->id],
            [
                'status' => 'pending',
                'proposed_price' => 1500000,
                'proposed_timeline' => '12 شهراً',
                'message' => 'نحن جاهزون للبدء في المشروع وتقديم أعلى معايير الجودة.',
            ]
        );

        // Request 2: Engineering Request by Seeker 2 (Accepted)
        $req2 = ServiceRequest::create([
            'user_id' => $seekers[1]->id,
            'category_id' => $engineeringCategory->id,
            'service_type' => 'engineering',
            'location' => 'جدة',
            'description' => 'ابحث عن مكتب لتصميم فيلا مودرن 300 متر.',
            'status' => 'provider_accepted', // This req is awarded
            'awarded_provider_id' => $providers[1]->id,
            'response_deadline' => now()->subDays(1),
            'dynamic_data' => ['style' => 'مدرن', 'area' => '300']
        ]);
        ServiceRequestResponse::firstOrCreate(
            ['service_request_id' => $req2->id, 'user_id' => $providers[1]->id],
            [
                'status' => 'accepted',
                'proposed_price' => 25000,
                'proposed_timeline' => '3 أسابيع',
                'message' => 'يمكننا تصميم الفيلا بشكل احترافي.',
            ]
        );

        // Request 3: Environment Request by Seeker 3
        $req3 = ServiceRequest::create([
            'user_id' => $seekers[2]->id,
            'category_id' => $environmentCategory->id,
            'service_type' => 'environment',
            'location' => 'الدمام',
            'description' => 'استشارة لضمان توافق المصنع مع المعايير البيئية.',
            'status' => 'pending',
            'response_deadline' => now()->addHours(24),
            'dynamic_data' => ['factory_type' => 'بلاستيك']
        ]);
        // Both provider 2 and 3 might respond, but let's just make provider 3 respond
        ServiceRequestResponse::firstOrCreate(
            ['service_request_id' => $req3->id, 'user_id' => $providers[2]->id],
            [
                'status' => 'pending',
                'proposed_price' => 5000,
                'proposed_timeline' => 'أسبوع',
                'message' => 'خبرتنا واسعة في هذا المجال ويمكننا مساعدتكم.',
            ]
        );

        // 7. Advanced Content & Categories
        $this->call([
            CategorySeeder::class,
            CategorySubSeeder::class,
            SubCategorySeeder::class,
        ]);
    }
}
