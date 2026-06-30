<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\City;
use App\Models\User;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
                              ->where('is_active', true)
                              ->get();
                              
        return view('website.categories.index', compact('categories'));
    }

    public function show(Request $request, $id)
    {
        $category = Category::with(['children' => function($q) {
            $q->where('is_active', true);
        }])->findOrFail($id);

        // Fetch providers who work in this category (or any of its children)
        $categoryIds = collect([$category->id]);
        if ($category->children->isNotEmpty()) {
            $categoryIds = $categoryIds->merge($category->children->pluck('id'));
        }

        $providersQuery = User::serviceProviders()
            ->where('users.active', 'active')
            ->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });

        // Filter by city if provided in query string
        if ($request->filled('city_id')) {
            $providersQuery->where('city_id', $request->city_id);
        }

        // Filter by subcategory if provided
        if ($request->filled('sub_category_id')) {
            $providersQuery->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->sub_category_id);
            });
        }

        $allProviders = $providersQuery
            ->select('users.*')
            ->leftJoin('subscription_packages', 'users.subscription_package_id', '=', 'subscription_packages.id')
            ->orderByRaw('COALESCE(subscription_packages.sort_order, 0) DESC')
            ->orderByDesc('id')
            ->withCount(['serviceRequestResponses as completed_projects_count' => function ($q) {
                $q->where('status', 'accepted')
                  ->whereHas('serviceRequest', function ($sq) {
                      $sq->whereIn('status', ['completed', 'work_completed']);
                  });
            }])
            ->get();

        $companyProviders = $allProviders->where('provider_type', 'company');
        $individualProviders = $allProviders->where('provider_type', 'individual');

        // For the search form dropdowns
        $cities = City::orderBy('name')->get();
        $subCategories = $category->children;

        if ($request->ajax()) {
            return view('website.categories.partials._providers_tabs_content', [
                'companyProviders' => $companyProviders,
                'individualProviders' => $individualProviders,
                'category' => $category
            ])->render();
        }

        return view('website.categories.show', [
            'category' => $category,
            'allProviders' => $allProviders,
            'companyProviders' => $companyProviders,
            'individualProviders' => $individualProviders,
            'cities' => $cities,
            'subCategories' => $subCategories
        ]);
    }
}
