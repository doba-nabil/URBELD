<?php

namespace App\Http\Controllers\Website\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SupplierOffer;
use Illuminate\Http\Request;

class SupplierOfferController extends Controller
{
    public function index()
    {
        $offers = auth()->user()->supplierOffers()->latest()->get();
        return view('website.profile.supplier.offers.index', compact('offers'));
    }

    public function create()
    {
        if (!auth()->user()->hasActiveSubscription()) {
            return redirect()->route('supplier.offers.index')->with('error', 'يجب أن يكون لديك اشتراك فعال لإضافة عروض.');
        }
        return view('website.profile.supplier.offers.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasActiveSubscription()) {
            return redirect()->route('supplier.offers.index')->with('error', 'يجب أن يكون لديك اشتراك فعال لإضافة عروض.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'badge_text' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $offer = auth()->user()->supplierOffers()->create($request->only('title', 'description', 'discount_percentage', 'badge_text'));

        if ($request->hasFile('image')) {
            $offer->addMediaFromRequest('image')
                ->preservingOriginal()
                ->toMediaCollection('offer_images');
        }

        return redirect()->route('supplier.offers.index')->with('success', __('admin.Success') ?? 'تمت الإضافة بنجاح');
    }

    public function edit(SupplierOffer $offer)
    {
        if ($offer->user_id !== auth()->id()) {
            abort(403);
        }
        return view('website.profile.supplier.offers.edit', compact('offer'));
    }

    public function update(Request $request, SupplierOffer $offer)
    {
        if ($offer->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'badge_text' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $offer->update($request->only('title', 'description', 'discount_percentage', 'badge_text'));

        if ($request->hasFile('image')) {
            $offer->clearMediaCollection('offer_images');
            $offer->addMediaFromRequest('image')
                ->preservingOriginal()
                ->toMediaCollection('offer_images');
        }

        return redirect()->route('supplier.offers.index')->with('success', __('admin.Success') ?? 'تم التعديل بنجاح');
    }

    public function destroy(SupplierOffer $offer)
    {
        if ($offer->user_id !== auth()->id()) {
            abort(403);
        }

        $offer->delete();
        return back()->with('success', __('admin.Success') ?? 'تم الحذف بنجاح');
    }
}
