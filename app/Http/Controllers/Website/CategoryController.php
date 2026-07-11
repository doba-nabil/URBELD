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
                              ->where('supports_supply_requests', false)
                              ->get();
                              
        return view('website.categories.index', compact('categories'));
    }

    public function show(Request $request, $id)
    {
        $category = Category::with(['children' => function($q) {
            $q->where('is_active', true)
              ->withCount(['users as providers_count' => function ($query) {
                  $query->whereIn('provider_type', ['company', 'individual'])
                        ->where('active', 'active');
              }]);
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

        // Filter by Region or City if provided in query string
        if ($request->filled('city_id')) {
            $providersQuery->where('city_id', $request->city_id);
        } elseif ($request->filled('region_id')) {
            $providersQuery->whereHas('city', function($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
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
            ->withCount(['serviceRequestResponses as completed_projects_count' => function ($q) {
                $q->where('status', 'accepted')
                  ->whereHas('serviceRequest', function ($sq) {
                      $sq->whereIn('status', ['completed', 'work_completed']);
                  });
            }]);

        // Handle Sorting
        $sort = $request->get('sort', 'premium');
        if ($sort === 'rating') {
            $allProviders = $allProviders->withAvg('ratingsReceived', 'rating')
                                         ->orderByDesc('ratings_received_avg_rating');
        } elseif ($sort === 'newest') {
            $allProviders = $allProviders->orderByDesc('users.created_at');
        } else {
            // Default to premium
            $allProviders = $allProviders->orderByRaw('COALESCE(subscription_packages.is_featured, 0) DESC')
                                         ->orderByRaw('COALESCE(subscription_packages.sort_order, 0) DESC');
        }
        
        $allProviders = $allProviders->orderByDesc('users.id')->get();

        $companyProviders = $allProviders->where('provider_type', 'company');
        $individualProviders = $allProviders->where('provider_type', 'individual');
        
        // Cache Category Statistics
        $statsClosure = function () use ($categoryIds) {
            $baseQuery = User::serviceProviders()
                ->where('users.active', 'active')
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            
            return [
                'companies' => (clone $baseQuery)->where('provider_type', 'company')->count(),
                'suppliers' => (clone $baseQuery)->where('provider_type', 'supplier')->count(),
                'premium' => (clone $baseQuery)->whereNotNull('subscription_package_id')
                                ->where('subscription_start_at', '<=', now())
                                ->where('subscription_end_at', '>=', now())->count(),
                'verified' => (clone $baseQuery)->where('is_trusted', 1)->count(),
            ];
        };

        if (\Illuminate\Support\Facades\Cache::supportsTags()) {
            $stats = \Illuminate\Support\Facades\Cache::tags(['category_stats'])->remember('category_stats_' . $category->id, 3600, $statsClosure);
        } else {
            $stats = \Illuminate\Support\Facades\Cache::remember('category_stats_' . $category->id, 3600, $statsClosure);
        }

        // For the search form dropdowns
        $cities = City::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
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
            'regions' => $regions,
            'subCategories' => $subCategories,
            'stats' => $stats
        ]);
    }
}
