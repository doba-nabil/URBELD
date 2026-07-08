<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use App\Models\SearchLog;

class ProviderSearchController extends Controller
{
    /**
     * Provider search results page.
     * Accepts GET parameters: category_id, city_id, keyword
     */
    public function index(Request $request)
    {
        // Build the provider query
        $providersQuery = User::serviceProviders()
            ->where('users.active', 'active');

        // Filter by category (main or sub)
        $selectedCategory = null;
        if ($request->filled('category_id')) {
            $selectedCategory = Category::find($request->category_id);
            if ($selectedCategory) {
                // Get the category and all its children IDs
                $categoryIds = collect([$selectedCategory->id]);
                $childIds = Category::where('parent_id', $selectedCategory->id)->pluck('id');
                $categoryIds = $categoryIds->merge($childIds);

                $providersQuery->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        // Filter by city
        if ($request->filled('city_id')) {
            $providersQuery->where('city_id', $request->city_id);
        }

        // Filter by keyword (name search)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $providersQuery->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('bio', 'LIKE', "%{$keyword}%");
            });
        }

        $allProviders = $providersQuery
            ->select('users.*')
            ->leftJoin('subscription_packages', 'users.subscription_package_id', '=', 'subscription_packages.id')
            ->orderByRaw('COALESCE(subscription_packages.sort_order, 0) DESC')
            ->orderByDesc('users.id')
            ->with(['city', 'subscriptionPackage', 'media', 'categories'])
            ->withCount(['serviceRequestResponses as completed_projects_count' => function ($q) {
                $q->where('status', 'accepted')
                  ->whereHas('serviceRequest', function ($sq) {
                      $sq->whereIn('status', ['completed', 'work_completed']);
                  });
            }])
            ->get();

        $companyProviders = $allProviders->where('provider_type', 'company');
        $individualProviders = $allProviders->where('provider_type', 'individual');

        // Log the search
        SearchLog::create([
            'user_id' => auth()->id(),
            'search_type' => $request->filled('keyword') || $request->filled('city_id') || $request->filled('category_id') ? 'advanced' : 'simple',
            'search_filters' => [
                'category_id' => $request->category_id,
                'city_id' => $request->city_id,
                'keyword' => $request->keyword,
            ],
            'results_count' => $allProviders->count(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // For the search form dropdowns
        $categories = Category::whereNull('parent_id')->where('is_active', true)->get();
        $cities = City::orderBy('name')->get();

        // If a main category is selected, load its subcategories
        $subCategories = collect();
        if ($selectedCategory) {
            $subCategories = $selectedCategory->parent_id 
                ? Category::where('parent_id', $selectedCategory->parent_id)->where('is_active', true)->get()
                : $selectedCategory->children()->where('is_active', true)->get();
        }

        return view('website.providers.search', [
            'providers' => $allProviders,
            'companyProviders' => $companyProviders,
            'individualProviders' => $individualProviders,
            'categories' => $categories,
            'cities' => $cities,
            'subCategories' => $subCategories,
            'selectedCategory' => $selectedCategory
        ]);
    }
}
