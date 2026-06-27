<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        $query = Service::active()
            ->join('users', 'services.user_id', '=', 'users.id')
            ->leftJoin('subscription_packages', 'users.subscription_package_id', '=', 'subscription_packages.id')
            ->select('services.*')
            ->orderByRaw('COALESCE(subscription_packages.sort_order, 0) DESC')
            ->orderBy('services.sort_order');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        $services = $query->with(['user', 'category', 'subCategory'])->paginate(12);
        $categories = Category::whereNull('parent_id')->where('is_active', true)->get();

        return view('website.services.index', compact('services', 'categories'));
    }

    /**
     * Display the specified service.
     */
    public function show($id)
    {
        $service = Service::active()->with(['user', 'category', 'subCategory'])->findOrFail($id);
        
        // Related services
        $relatedServices = Service::active()
            ->where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->limit(4)
            ->get();

        return view('website.services.show', compact('service', 'relatedServices'));
    }
}
