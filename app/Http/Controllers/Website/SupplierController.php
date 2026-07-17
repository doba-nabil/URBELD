<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use App\Models\CompanyClassification;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategoryId = $request->input('category_id');
        $selectedCategory = null;
        if ($selectedCategoryId) {
            $selectedCategory = Category::find($selectedCategoryId);
        }

        $baseCategoryQuery = Category::with(['children' => function($q) {
            $q->where('is_active', true);
        }])
        ->where('is_active', true)
        ->withCount(['users as providers_count' => function ($query) {
            $query->where('provider_type', 'supplier')->where('active', 'active');
        }]);

        if ($selectedCategory && $selectedCategory->parent_id === null) {
            // Selected a main category -> Show its subcategories
            $supplyCategories = (clone $baseCategoryQuery)->where('parent_id', $selectedCategory->id)->get();
        } elseif ($selectedCategory && $selectedCategory->parent_id !== null) {
            // Selected a sub category -> Show siblings
            $supplyCategories = (clone $baseCategoryQuery)->where('parent_id', $selectedCategory->parent_id)->get();
        } else {
            // Show main categories
            $supplyCategories = (clone $baseCategoryQuery)->whereNull('parent_id')->where('supports_supply_requests', true)->get();
        }

        // Removed fallback so it accurately shows no subcategories if none exist.
        
        $categoryIds = $supplyCategories->pluck('id');

        // 2. Build Providers Query (Only Suppliers)
        $providersQuery = User::serviceProviders()
            ->where('provider_type', 'supplier')
            ->where('users.active', 'active');
            
        if ($selectedCategory) {
            $filterIds = [$selectedCategory->id];
            if ($selectedCategory->parent_id === null) {
                // include children in filter
                $filterIds = array_merge($filterIds, Category::where('parent_id', $selectedCategory->id)->pluck('id')->toArray());
            }
            $providersQuery->whereHas('categories', function ($q) use ($filterIds) {
                $q->whereIn('categories.id', $filterIds);
            });
        } else {
            $providersQuery->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // 3. Filter by Region or City
        if ($request->filled('city_id')) {
            $providersQuery->where('city_id', $request->city_id);
        } elseif ($request->filled('region_id')) {
            $providersQuery->whereHas('city', function($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }

        // 4. Filter by Volume / Company Classification
        if ($request->filled('classification_id')) {
            $providersQuery->where('classification_id', $request->classification_id);
        }

        // 5. Order and execute query
        $suppliersQuery = $providersQuery
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
            $suppliersQuery = $suppliersQuery->withAvg('ratingsReceived', 'rating')
                                             ->orderByDesc('ratings_received_avg_rating');
        } elseif ($sort === 'newest') {
            $suppliersQuery = $suppliersQuery->orderByDesc('users.created_at');
        } else {
            // Default to premium
            $suppliersQuery = $suppliersQuery->orderByRaw('COALESCE(subscription_packages.is_featured, 0) DESC')
                                             ->orderByRaw('COALESCE(subscription_packages.sort_order, 0) DESC');
        }
        
        $suppliers = $suppliersQuery->orderByDesc('users.id')->get();

        // 6. Data for Dropdowns
        $cities = City::all();
        $regions = \App\Models\Region::all();
        $classifications = CompanyClassification::where('type', 'supplier')->get();


        if ($request->ajax()) {
            return view('website.suppliers.partials._providers_list', [
                'suppliers' => $suppliers,
            ])->render();
        }

        return view('website.suppliers.index', compact('suppliers', 'supplyCategories', 'cities', 'regions', 'classifications', 'selectedCategory'));
    }
}
