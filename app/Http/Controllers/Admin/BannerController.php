<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\SupplierOffer;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::with(['supplierOffer', 'category'])->orderBy('sort_order')->paginate(15);
        return view('dashboard.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        // Here you might fetch active supplier offers if you want to link a banner to one
        $supplierOffers = SupplierOffer::with('user')->where('status', 'active')->latest()->take(50)->get();
        
        return view('dashboard.banners.create', compact('categories', 'supplierOffers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'supplier_offer_id' => 'nullable|exists:supplier_offers,id',
            'page_scope' => 'required|string|in:' . implode(',', array_keys(Banner::scopeLabels())),
            'category_id' => 'nullable|required_if:page_scope,' . Banner::SCOPE_SPECIFIC_CATEGORY . '|exists:categories,id',
            'custom_page' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $banner = Banner::create($validated);

        if ($request->hasFile('banner_image')) {
            $banner->addMediaFromRequest('banner_image')->toMediaCollection('banner_image');
        }

        // Invalidate cache
        Cache::tags(['banners'])->flush();

        return redirect()->route('banners.index')->with('success', 'تم إضافة البانر بنجاح وتحديث الكاش.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        $categories = Category::where('is_active', true)->get();
        $supplierOffers = SupplierOffer::with('user')->where('status', 'active')->latest()->take(50)->get();
        
        return view('dashboard.banners.edit', compact('banner', 'categories', 'supplierOffers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'supplier_offer_id' => 'nullable|exists:supplier_offers,id',
            'page_scope' => 'required|string|in:' . implode(',', array_keys(Banner::scopeLabels())),
            'category_id' => 'nullable|required_if:page_scope,' . Banner::SCOPE_SPECIFIC_CATEGORY . '|exists:categories,id',
            'custom_page' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // Fix boolean if unchecked
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $banner->update($validated);

        if ($request->hasFile('banner_image')) {
            $banner->clearMediaCollection('banner_image');
            $banner->addMediaFromRequest('banner_image')->toMediaCollection('banner_image');
        }

        // Invalidate cache
        Cache::tags(['banners'])->flush();

        return redirect()->route('banners.index')->with('success', 'تم تحديث البانر بنجاح وتحديث الكاش.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        
        // Invalidate cache
        Cache::tags(['banners'])->flush();

        return redirect()->route('banners.index')->with('success', 'تم حذف البانر بنجاح وتحديث الكاش.');
    }
}
