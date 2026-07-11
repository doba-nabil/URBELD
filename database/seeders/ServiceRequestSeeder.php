<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\Category;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use App\Models\ServiceRequestInspection;
use App\Models\Rating;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        // Get categories
        $contractingCategory = Category::where('slug', 'contracting')->first();
        $engineeringCategory = Category::where('slug', 'engineering-consulting')->first();
        $environmentCategory = Category::where('slug', 'environment')->first();
        // Get or create service seekers
        $serviceSeekers = [];
        for ($i = 1; $i <= 5; $i++) {
            $serviceSeekers[] = User::firstOrCreate(
                ['email' => "seeker{$i}@example.com"],
                [
                    'name' => "طالب خدمة {$i}",
                    'email' => "seeker{$i}@example.com",
                    'password' => bcrypt('password'),
                    'phone' => "050000000{$i}",
                    'user_type' => 'service_seeker',
                    'active' => true,
                ]
            );
        }
        // Get or create service providers
        $serviceProviders = [];
        for ($i = 1; $i <= 10; $i++) {
            $providerType = $i % 2 == 0 ? 'company' : 'individual';
            $serviceProviders[] = User::firstOrCreate(
                ['email' => "provider{$i}@example.com"],
                [
                    'name' => $providerType == 'company' ? "شركة {$i}" : "مهندس {$i}",
                    'email' => "provider{$i}@example.com",
                    'password' => bcrypt('password'),
                    'phone' => "050000001{$i}",
                    'user_type' => 'service_provider',
                    'provider_type' => $providerType,
                    'active' => true,
                ]
            );
        }
        // Create requests with different statuses
        $requests = [];
        $requests[] = ServiceRequest::create([
            'user_id' => $serviceSeekers[0]->id,
            'category_id' => $contractingCategory->id,
            'status' => ServiceRequest::STATUS_NEW,
            'property_type' => 'residential',
            'area' => 150.50,
            'location' => 'الرياض، حي النرجس',
            'latitude' => '24.7136',
            'longitude' => '46.6753',
            'description' => 'طلب مقاولات عامة لبناء منزل سكني',
            'blueprint_description' => 'رسم كروكي للمنزل',
            'response_deadline' => Carbon::now()->addHours(48),
        ]);
        $pendingRequest = ServiceRequest::create([
            'user_id' => $serviceSeekers[1]->id,
            'category_id' => $engineeringCategory->id,
            'status' => ServiceRequest::STATUS_PENDING_RESPONSE,
            'property_type' => 'commercial',
            'area' => 300.00,
            'location' => 'جدة، حي الزهراء',
            'latitude' => '21.4858',
            'longitude' => '39.1925',
            'description' => 'طلب استشارات هندسية لمشروع تجاري',
            'site_photos_description' => 'صور الموقع',
            'response_deadline' => Carbon::now()->addHours(24),
        ]);
        // Add responses to pending request
        ServiceRequestResponse::create([
            'service_request_id' => $pendingRequest->id,
            'user_id' => $serviceProviders[0]->id,
            'status' => ServiceRequestResponse::STATUS_PENDING,
            'message' => 'نحن مستعدون للعمل على هذا المشروع',
            'proposed_price' => 50000.00,
            'proposed_timeline' => '3 أشهر',
            'responded_at' => Carbon::now()->subHours(5),
        ]);
        ServiceRequestResponse::create([
            'service_request_id' => $pendingRequest->id,
            'user_id' => $serviceProviders[1]->id,
            'status' => ServiceRequestResponse::STATUS_PENDING,
            'message' => 'عرضنا مميز وجاهز للبدء',
            'proposed_price' => 45000.00,
            'proposed_timeline' => '2.5 شهر',
            'responded_at' => Carbon::now()->subHours(3),
        ]);
        $acceptedRequest = ServiceRequest::create([
            'user_id' => $serviceSeekers[2]->id,
            'category_id' => $contractingCategory->id,
            'status' => ServiceRequest::STATUS_ACCEPTED,
            'property_type' => 'residential',
            'area' => 200.00,
            'location' => 'الدمام، حي الفيصلية',
            'latitude' => '26.4207',
            'longitude' => '50.0888',
            'description' => 'طلب مقاولات كهرباء',
            'blueprint_description' => 'رسم كروكي للتمديدات الكهربائية',
            'response_deadline' => Carbon::now()->subHours(10),
            'accepted_at' => Carbon::now()->subHours(2),
        ]);
        $acceptedResponse = ServiceRequestResponse::create([
            'service_request_id' => $acceptedRequest->id,
            'user_id' => $serviceProviders[2]->id,
            'status' => ServiceRequestResponse::STATUS_ACCEPTED,
            'message' => 'تم قبول العرض',
            'proposed_price' => 30000.00,
            'proposed_timeline' => 'شهر واحد',
            'responded_at' => Carbon::now()->subHours(5),
        ]);
        $inspectionRequest = ServiceRequest::create([
            'user_id' => $serviceSeekers[3]->id,
            'category_id' => $engineeringCategory->id,
            'status' => ServiceRequest::STATUS_UNDER_INSPECTION,
            'property_type' => 'industrial',
            'area' => 500.00,
            'location' => 'الرياض، حي العليا',
            'latitude' => '24.7136',
            'longitude' => '46.6753',
            'description' => 'طلب استشارات إنشائية',
            'site_photos_description' => 'صور الموقع الصناعي',
            'response_deadline' => Carbon::now()->subDays(1),
            'accepted_at' => Carbon::now()->subDays(2),
        ]);
        $inspectionResponse = ServiceRequestResponse::create([
            'service_request_id' => $inspectionRequest->id,
            'user_id' => $serviceProviders[3]->id,
            'status' => ServiceRequestResponse::STATUS_ACCEPTED,
            'message' => 'تم قبول العرض وجاهزون للمعاينة',
            'proposed_price' => 80000.00,
            'proposed_timeline' => '4 أشهر',
            'responded_at' => Carbon::now()->subDays(2),
        ]);
        ServiceRequestInspection::create([
            'service_request_id' => $inspectionRequest->id,
            'response_id' => $inspectionResponse->id,
            'scheduled_at' => Carbon::now()->addDays(2),
            'status' => ServiceRequestInspection::STATUS_SCHEDULED,
            'notes' => 'معاينة الموقع المقررة',
        ]);
        $agreedRequest = ServiceRequest::create([
            'user_id' => $serviceSeekers[4]->id,
            'category_id' => $contractingCategory->id,
            'status' => ServiceRequest::STATUS_AGREED,
            'property_type' => 'residential',
            'area' => 180.00,
            'location' => 'الرياض، حي المطار',
            'latitude' => '24.7136',
            'longitude' => '46.6753',
            'description' => 'طلب مقاولات سباكة',
            'blueprint_description' => 'رسم كروكي للتمديدات الصحية',
            'response_deadline' => Carbon::now()->subDays(5),
            'accepted_at' => Carbon::now()->subDays(4),
        ]);
        $agreedResponse = ServiceRequestResponse::create([
            'service_request_id' => $agreedRequest->id,
            'user_id' => $serviceProviders[4]->id,
            'status' => ServiceRequestResponse::STATUS_ACCEPTED,
            'message' => 'تم الاتفاق وبدء العمل',
            'proposed_price' => 25000.00,
            'proposed_timeline' => '3 أسابيع',
            'responded_at' => Carbon::now()->subDays(4),
        ]);
        ServiceRequestInspection::create([
            'service_request_id' => $agreedRequest->id,
            'response_id' => $agreedResponse->id,
            'scheduled_at' => Carbon::now()->subDays(3),
            'completed_at' => Carbon::now()->subDays(2),
            'status' => ServiceRequestInspection::STATUS_COMPLETED,
            'notes' => 'تمت المعاينة بنجاح',
        ]);
        $completedRequest = ServiceRequest::create([
            'user_id' => $serviceSeekers[0]->id,
            'category_id' => $engineeringCategory->id,
            'status' => ServiceRequest::STATUS_COMPLETED,
            'property_type' => 'commercial',
            'area' => 400.00,
            'location' => 'جدة، حي الروابي',
            'latitude' => '21.4858',
            'longitude' => '39.1925',
            'description' => 'طلب استشارات معمارية',
            'site_photos_description' => 'صور الموقع التجاري',
            'response_deadline' => Carbon::now()->subDays(30),
            'accepted_at' => Carbon::now()->subDays(28),
            'completed_at' => Carbon::now()->subDays(1),
        ]);
        $completedResponse = ServiceRequestResponse::create([
            'service_request_id' => $completedRequest->id,
            'user_id' => $serviceProviders[5]->id,
            'status' => ServiceRequestResponse::STATUS_ACCEPTED,
            'message' => 'تم إتمام العمل بنجاح',
            'proposed_price' => 60000.00,
            'proposed_timeline' => 'شهرين',
            'responded_at' => Carbon::now()->subDays(28),
        ]);
        ServiceRequestInspection::create([
            'service_request_id' => $completedRequest->id,
            'response_id' => $completedResponse->id,
            'scheduled_at' => Carbon::now()->subDays(25),
            'completed_at' => Carbon::now()->subDays(24),
            'status' => ServiceRequestInspection::STATUS_COMPLETED,
            'notes' => 'تمت المعاينة والموافقة',
        ]);
        // Add ratings for completed request
        Rating::create([
            'service_request_id' => $completedRequest->id,
            'rater_id' => $serviceSeekers[0]->id,
            'rated_id' => $serviceProviders[5]->id,
            'rating' => 5,
            'comment' => 'عمل ممتاز ومهني',
        ]);
        Rating::create([
            'service_request_id' => $completedRequest->id,
            'rater_id' => $serviceProviders[5]->id,
            'rated_id' => $serviceSeekers[0]->id,
            'rating' => 4,
            'comment' => 'عميل محترم ومتعاون',
        ]);
        $rejectedRequest = ServiceRequest::create([
            'user_id' => $serviceSeekers[1]->id,
            'category_id' => $contractingCategory->id,
            'status' => ServiceRequest::STATUS_REJECTED,
            'property_type' => 'residential',
            'area' => 120.00,
            'location' => 'الرياض، حي العريجاء',
            'latitude' => '24.7136',
            'longitude' => '46.6753',
            'description' => 'طلب مقاولات دهانات',
            'blueprint_description' => 'رسم كروكي للدهانات',
            'response_deadline' => Carbon::now()->subDays(3),
        ]);
        ServiceRequestResponse::create([
            'service_request_id' => $rejectedRequest->id,
            'user_id' => $serviceProviders[6]->id,
            'status' => ServiceRequestResponse::STATUS_REJECTED,
            'message' => 'عذراً، لا نستطيع العمل على هذا المشروع',
            'proposed_price' => 0,
            'proposed_timeline' => null,
            'responded_at' => Carbon::now()->subDays(2),
        ]);
        $expiredRequest = ServiceRequest::create([
            'user_id' => $serviceSeekers[2]->id,
            'category_id' => $environmentCategory->id,
            'status' => ServiceRequest::STATUS_TIME_EXPIRED,
            'property_type' => 'commercial',
            'area' => 250.00,
            'location' => 'الدمام، حي الشاطئ',
            'latitude' => '26.4207',
            'longitude' => '50.0888',
            'description' => 'طلب دراسات بيئية',
            'neighbors_description' => 'وصف الجيران',
            'response_deadline' => Carbon::now()->subDays(3),
        ]);
        // 9. More requests for statistics
        for ($i = 0; $i < 5; $i++) {
            ServiceRequest::create([
                'user_id' => $serviceSeekers[array_rand($serviceSeekers)]->id,
                'category_id' => $contractingCategory->id,
                'status' => ServiceRequest::STATUS_NEW,
                'property_type' => ['residential', 'commercial', 'industrial'][array_rand(['residential', 'commercial', 'industrial'])],
                'area' => rand(100, 500),
                'location' => 'موقع عشوائي ' . ($i + 1),
                'latitude' => '24.' . rand(7000, 8000),
                'longitude' => '46.' . rand(6000, 7000),
                'description' => 'طلب مقاولات ' . ($i + 1),
                'blueprint_description' => 'رسم كروكي ' . ($i + 1),
                'response_deadline' => Carbon::now()->addHours(rand(12, 48)),
            ]);
        }
        for ($i = 0; $i < 3; $i++) {
            $req = ServiceRequest::create([
                'user_id' => $serviceSeekers[array_rand($serviceSeekers)]->id,
                'category_id' => $engineeringCategory->id,
                'status' => ServiceRequest::STATUS_PENDING_RESPONSE,
                'property_type' => ['residential', 'commercial'][array_rand(['residential', 'commercial'])],
                'area' => rand(200, 400),
                'location' => 'موقع استشارات ' . ($i + 1),
                'latitude' => '21.' . rand(4000, 5000),
                'longitude' => '39.' . rand(1000, 2000),
                'description' => 'طلب استشارات ' . ($i + 1),
                'site_photos_description' => 'صور الموقع ' . ($i + 1),
                'response_deadline' => Carbon::now()->addHours(rand(12, 36)),
            ]);
            // Add some responses
            ServiceRequestResponse::create([
                'service_request_id' => $req->id,
                'user_id' => $serviceProviders[array_rand($serviceProviders)]->id,
                'status' => ServiceRequestResponse::STATUS_PENDING,
                'message' => 'عرض للطلب ' . ($i + 1),
                'proposed_price' => rand(20000, 100000),
                'proposed_timeline' => rand(1, 6) . ' أشهر',
                'responded_at' => Carbon::now()->subHours(rand(1, 10)),
            ]);
        }
    }
}
