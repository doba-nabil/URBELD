<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = \App\Models\SupplierOffer::with('user')->latest()->paginate(15);
        return view('dashboard.supplier-offers.index', compact('offers'));
    }

    public function create()
    {
        $suppliers = \App\Models\User::where('provider_type', 'supplier')->get();
        return view('dashboard.supplier-offers.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:5120',
        ]);

        $offer = \App\Models\SupplierOffer::create($validated);

        if ($request->hasFile('image')) {
            $offer->addMedia($request->file('image'))->toMediaCollection('offer_images');
        }

        return redirect()->route('supplier-offers.index')->with('success', __('admin.create_success'));
    }

    public function edit(string $id)
    {
        $offer = \App\Models\SupplierOffer::findOrFail($id);
        $suppliers = \App\Models\User::where('provider_type', 'supplier')->get();
        return view('dashboard.supplier-offers.edit', compact('offer', 'suppliers'));
    }

    public function update(Request $request, string $id)
    {
        $offer = \App\Models\SupplierOffer::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:5120',
        ]);

        $offer->update($validated);

        if ($request->hasFile('image')) {
            $offer->clearMediaCollection('offer_images');
            $offer->addMedia($request->file('image'))->toMediaCollection('offer_images');
        }

        return redirect()->route('supplier-offers.index')->with('success', __('admin.update_success'));
    }

    public function destroy(string $id)
    {
        $offer = \App\Models\SupplierOffer::findOrFail($id);
        $offer->delete();
        return redirect()->route('supplier-offers.index')->with('success', __('admin.delete_success'));
    }
}
