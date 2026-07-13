<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\App\DataTables\SupplierProductDataTable $dataTable)
    {
        return $dataTable->render('dashboard.supplier-products.index');
    }

    public function create()
    {
        $suppliers = \App\Models\User::where('provider_type', 'supplier')->get();
        return view('dashboard.supplier-products.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        $product = \App\Models\Product::create($validated);

        if ($request->hasFile('image')) {
            $product->addMedia($request->file('image'))->toMediaCollection('product_images');
        }

        return redirect()->route('supplier-products.index')->with('success', __('admin.create_success'));
    }

    public function edit(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $suppliers = \App\Models\User::where('provider_type', 'supplier')->get();
        return view('dashboard.supplier-products.edit', compact('product', 'suppliers'));
    }

    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        $product->update($validated);

        if ($request->hasFile('image')) {
            $product->clearMediaCollection('product_images');
            $product->addMedia($request->file('image'))->toMediaCollection('product_images');
        }

        return redirect()->route('supplier-products.index')->with('success', __('admin.update_success'));
    }

    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        return redirect()->route('supplier-products.index')->with('success', __('admin.delete_success'));
    }
}
