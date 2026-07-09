<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use App\Models\Membership;
use App\Models\Category;
use App\Models\Service;
use App\Models\SuccessPartner;
use App\Models\Contact;
use App\Models\Rating;
use App\Models\ServiceRequestInspection;
use App\Traits\HasActivityFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use HasActivityFeed;

    public function index()
    {
        // Date ranges
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        $lastYear = Carbon::now()->subYear()->startOfYear();

        // Users Statistics
        $totalUsers = User::count();
        $serviceSeekers = User::where('user_type', 'service_seeker')->count();
        $serviceProviders = User::where('user_type', 'service_provider')->count();
        $individualProviders = User::where('user_type', 'service_provider')->where('provider_type', 'individual')->count();
        $companyProviders = User::where('user_type', 'service_provider')->where('provider_type', 'company')->count();
        $activeUsers = User::where('active', true)->count();
        $usersWithActiveMembership = User::whereNotNull('membership_id')
            ->where('membership_expires_at', '>', now())
            ->count();
        $totalSuppliers = User::where('user_type', 'service_provider')->where('provider_type', 'supplier')->count();
        $totalProducts = \App\Models\Product::count();
        $totalSupplierOffers = \App\Models\SupplierOffer::count();

        // Tenders Statistics
        $totalTenders = \App\Models\Tender::count();
        $activeTenders = \App\Models\Tender::where('status', \App\Models\Tender::STATUS_ACTIVE)->count();
        $pendingTenders = \App\Models\Tender::where('status', \App\Models\Tender::STATUS_PENDING_REVIEW)->count();

        // Service Requests Statistics
        $totalRequests = ServiceRequest::count();
        $newRequests = ServiceRequest::where('status', ServiceRequest::STATUS_PENDING)->count();
        $pendingResponseRequests = ServiceRequest::where('status', ServiceRequest::STATUS_PENDING)->count();
        $acceptedRequests = ServiceRequest::where('status', ServiceRequest::STATUS_PROVIDER_ACCEPTED)->count();
        $underInspectionRequests = ServiceRequest::where('status', ServiceRequest::STATUS_INSPECTION_SCHEDULED)->count();
        $agreedRequests = ServiceRequest::where('status', ServiceRequest::STATUS_SEEKER_CONFIRMED)->count();
        $completedRequests = ServiceRequest::where('status', ServiceRequest::STATUS_COMPLETED)->count();
        $expiredRequests = ServiceRequest::where('status', ServiceRequest::STATUS_TIME_EXPIRED)->count();

        // Responses Statistics
        $totalResponses = ServiceRequestResponse::count();
        $pendingResponses = ServiceRequestResponse::where('status', ServiceRequestResponse::STATUS_PENDING)->count();
        $acceptedResponses = ServiceRequestResponse::where('status', ServiceRequestResponse::STATUS_ACCEPTED)->count();
        $rejectedResponses = ServiceRequestResponse::where('status', ServiceRequestResponse::STATUS_REJECTED)->count();

        // Inspections Statistics
        $totalInspections = ServiceRequestInspection::count();
        $scheduledInspections = ServiceRequestInspection::where('status', ServiceRequestInspection::STATUS_SCHEDULED)->count();
        $completedInspections = ServiceRequestInspection::where('status', ServiceRequestInspection::STATUS_COMPLETED)->count();

        // Financial Statistics (Total Revenue/Agreed Value)
        $totalRevenue = ServiceRequestResponse::where('status', ServiceRequestResponse::STATUS_ACCEPTED)
            ->whereHas('serviceRequest', function ($query) {
                $query->whereIn('status', [ServiceRequest::STATUS_COMPLETED, ServiceRequest::STATUS_SEEKER_CONFIRMED]);
            })
            ->sum('proposed_price');

        // Ratings Statistics
        $totalRatings = Rating::count();
        $averageRating = Rating::avg('rating') ?? 0;

        // Memberships Statistics
        $totalMemberships = Membership::count();
        $activeMemberships = Membership::where('is_active', true)->count();
        $individualMemberships = Membership::where('type', 'individual')->count();
        $companyMemberships = Membership::where('type', 'company')->count();

        // Categories Statistics
        $totalCategories = Category::count();
        $mainCategories = Category::whereNull('parent_id')->count();
        $subCategories = Category::whereNotNull('parent_id')->count();

        // Services & Partners Statistics
        $totalServices = Service::where('is_active', true)->count();
        $totalPartners = SuccessPartner::where('is_active', true)->count();

        // Other Statistics
        $totalContacts = Contact::count();
        $totalVisitors = \App\Models\Visitor::count();
        $todayVisitors = \App\Models\Visitor::where('visited_date', now()->toDateString())->count();

        // Recent Requests
        $recentRequests = ServiceRequest::with(['user', 'category'])
            ->latest()
            ->limit(10)
            ->get();

        // Top Service Providers (by Rating and Completed projects)
        $topProviders = User::where('user_type', 'service_provider')
            ->withCount(['serviceRequestResponses as completed_projects' => function ($query) {
                $query->where('status', ServiceRequestResponse::STATUS_ACCEPTED);
            }])
            ->withAvg('ratingsReceived as average_rating', 'rating')
            ->orderByDesc('completed_projects')
            ->orderByDesc('average_rating')
            ->limit(5)
            ->get();

        // Recent Activity Feed
        $recentActivityFeed = $this->getActivityFeed(8);

        // Requests by Category
        $requestsByCategory = ServiceRequest::select('category_id', DB::raw('count(*) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                // Get category name as string (translated)
                $categoryName = $item->category ? $item->category->name : '-';
                // If name is still an array (JSON), get Arabic version
                if (is_array($categoryName)) {
                    $categoryName = $categoryName['ar'] ?? $categoryName['en'] ?? '-';
                }
                return [
                    'category_id' => $item->category_id,
                    'total' => $item->total,
                    'category_name' => $categoryName,
                ];
            });

        // Requests by Status
        $requestsByStatus = ServiceRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Monthly Statistics
        $monthlyRequests = ServiceRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthlyUsers = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthlyResponses = ServiceRequestResponse::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthlyCompleted = ServiceRequest::where('status', ServiceRequest::STATUS_COMPLETED)
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->count();

        // Last Month Statistics (for comparison)
        $lastMonthRequests = ServiceRequest::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $lastMonthUsers = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        // Yearly Statistics
        $yearlyRequests = ServiceRequest::whereYear('created_at', now()->year)->count();
        $yearlyUsers = User::whereYear('created_at', now()->year)->count();
        $yearlyResponses = ServiceRequestResponse::whereYear('created_at', now()->year)->count();
        $yearlyCompleted = ServiceRequest::where('status', ServiceRequest::STATUS_COMPLETED)
            ->whereYear('completed_at', now()->year)
            ->count();

        // Last Year Statistics (for comparison)
        $lastYearRequests = ServiceRequest::whereYear('created_at', now()->subYear()->year)->count();
        $lastYearUsers = User::whereYear('created_at', now()->subYear()->year)->count();

        // Monthly Requests Chart Data (Last 12 months)
        $monthlyRequestsData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyRequestsData[] = [
                'month' => $date->format('M'),
                'month_ar' => $this->getArabicMonth($date->month),
                'count' => ServiceRequest::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        // Weekly Requests Chart Data (Last 7 days)
        $weeklyRequestsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyRequestsData[] = [
                'day' => $date->format('D'),
                'day_ar' => $this->getArabicDay($date->dayOfWeek),
                'count' => ServiceRequest::whereDate('created_at', $date->toDateString())->count()
            ];
        }

        // 30 Days Requests Chart Data (Last 30 days)
        $last30DaysRequestsData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last30DaysRequestsData[] = [
                'date' => $date->format('Y-m-d'),
                'day_ar' => $this->getArabicDay($date->dayOfWeek),
                'count' => ServiceRequest::whereDate('created_at', $date->toDateString())->count()
            ];
        }

        return view('dashboard.home', compact(
            'totalUsers',
            'serviceSeekers',
            'serviceProviders',
            'individualProviders',
            'companyProviders',
            'activeUsers',
            'usersWithActiveMembership',
            'totalSuppliers',
            'totalProducts',
            'totalSupplierOffers',
            'totalTenders',
            'activeTenders',
            'pendingTenders',
            'totalRequests',
            'newRequests',
            'pendingResponseRequests',
            'acceptedRequests',
            'underInspectionRequests',
            'agreedRequests',
            'completedRequests',
            'expiredRequests',
            'totalResponses',
            'pendingResponses',
            'acceptedResponses',
            'rejectedResponses',
            'totalInspections',
            'scheduledInspections',
            'completedInspections',
            'totalRatings',
            'averageRating',
            'totalMemberships',
            'activeMemberships',
            'individualMemberships',
            'companyMemberships',
            'totalCategories',
            'mainCategories',
            'subCategories',
            'totalServices',
            'totalPartners',
            'totalContacts',
            'totalVisitors',
            'todayVisitors',
            'recentRequests',
            'topProviders',
            'requestsByCategory',
            'requestsByStatus',
            'monthlyRequests',
            'monthlyUsers',
            'monthlyResponses',
            'monthlyCompleted',
            'lastMonthRequests',
            'lastMonthUsers',
            'yearlyRequests',
            'yearlyUsers',
            'yearlyResponses',
            'yearlyCompleted',
            'lastYearRequests',
            'lastYearUsers',
            'monthlyRequestsData',
            'weeklyRequestsData',
            'last30DaysRequestsData',
            'totalRevenue',
            'recentActivityFeed'
        ));
    }

    public function reports(Request $request)
    {
        $dateRange = $request->get('date_range', 'monthly'); // daily, weekly, monthly, yearly
        $data = $this->getReportData($dateRange);
        
        return view('dashboard.reports.index', compact('data', 'dateRange'));
    }

    public function exportReports(Request $request)
    {
        $dateRange = $request->get('date_range', 'monthly');
        $data = $this->getReportData($dateRange);
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DashboardReportExport($data), 'admin_reports_' . $dateRange . '_' . date('Y-m-d') . '.xlsx');
    }

    private function getReportData($dateRange)
    {
        $startDate = match ($dateRange) {
            'daily' => Carbon::today(),
            'weekly' => Carbon::now()->startOfWeek(),
            'yearly' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(), // monthly
        };

        // Queries based on startDate
        $newRequests = ServiceRequest::where('created_at', '>=', $startDate)->count();
        $completedRequests = ServiceRequest::where('status', ServiceRequest::STATUS_COMPLETED)
            ->where('completed_at', '>=', $startDate)->count();

        $revenue = ServiceRequestResponse::where('status', ServiceRequestResponse::STATUS_ACCEPTED)
            ->whereHas('serviceRequest', function ($query) use ($startDate) {
                $query->whereIn('status', [ServiceRequest::STATUS_COMPLETED, ServiceRequest::STATUS_SEEKER_CONFIRMED])
                      ->where('created_at', '>=', $startDate);
            })->sum('proposed_price');

        $newUsers = User::where('created_at', '>=', $startDate)->count();

        // Top Providers (Most Accepted Responses)
        $topProviders = User::where('user_type', 'service_provider')
            ->withCount(['serviceRequestResponses as accepted_responses' => function ($query) use ($startDate) {
                $query->where('status', ServiceRequestResponse::STATUS_ACCEPTED)
                      ->where('created_at', '>=', $startDate);
            }])
            ->having('accepted_responses', '>', 0)
            ->orderByDesc('accepted_responses')
            ->limit(20)
            ->get();

        // Top Seekers (Most Created Requests)
        $topSeekers = User::where('user_type', 'service_seeker')
            ->withCount(['serviceRequests as total_requests' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }])
            ->having('total_requests', '>', 0)
            ->orderByDesc('total_requests')
            ->limit(20)
            ->get();

        return compact('newRequests', 'completedRequests', 'revenue', 'newUsers', 'topProviders', 'topSeekers', 'dateRange', 'startDate');
    }

    private function getArabicMonth($month)
    {
        $months = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];
        return $months[$month] ?? '';
    }

    private function getArabicDay($dayOfWeek)
    {
        $days = [
            0 => 'الأحد', 1 => 'الإثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء',
            4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'
        ];
        return $days[$dayOfWeek] ?? '';
    }
}

