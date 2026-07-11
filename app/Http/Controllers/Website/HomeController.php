<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $settingModel = Setting::where('key', 'media')->first();
        $logoUrl = $settingModel?->getFirstMediaUrl('logo');

        $siteName = Setting::getValue('site_name', app()->getLocale(), 'أوربلد');


        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('show_in_home', true)
            ->orderBy('sort_order', 'asc')
            ->get();
        $successPartners = \App\Models\SuccessPartner::active()->ordered()->get();

        $topProviders = \App\Models\User::where('user_type', 'service_provider')
            ->where('provider_type', 'company')
            ->where('active', 'active') // Ensure they are approved
            ->select('users.*')
            ->with(['categories'])
            ->withCount(['serviceRequestResponses as completed_projects_count' => function ($query) {
                $query->where('status', \App\Models\ServiceRequestResponse::STATUS_ACCEPTED)
                    ->whereHas('serviceRequest', function ($q) {
                        $q->whereIn('status', ['completed', 'work_completed']);
                    });
            }])
            ->leftJoin('subscription_packages', 'users.subscription_package_id', '=', 'subscription_packages.id')
            ->orderByDesc('completed_projects_count')
            ->orderByRaw('COALESCE(subscription_packages.sort_order, 0) DESC')
            ->limit(3)
            ->get();

        $activeServices = \App\Models\Service::active()
            ->join('users', 'services.user_id', '=', 'users.id')
            ->where('users.provider_type', 'company')
            ->leftJoin('subscription_packages', 'users.subscription_package_id', '=', 'subscription_packages.id')
            ->select('services.*')
            ->orderByRaw('COALESCE(subscription_packages.sort_order, 0) DESC')
            ->orderBy('services.sort_order')
            ->with(['user', 'category'])
            ->limit(6)->get();

        $banners = \App\Models\Banner::getForPage('home');
        $activeTenders = \App\Models\Tender::active()->latest()->limit(6)->get();

        return view('website.index', compact('logoUrl', 'siteName', 'categories', 'successPartners', 'topProviders', 'activeServices', 'banners', 'activeTenders'));
    }
}
