<?php

namespace App\Http\Controllers\Website\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products()->latest()->get();
        return view('website.profile.supplier.products.index', compact('products'));
    }

    public function create()
    {
        return view('website.profile.supplier.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $product = auth()->user()->products()->create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'price' => $request->price,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $product->addMedia($file)
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->preservingOriginal()
                    ->toMediaCollection('product_images');
            }
        }

        return redirect()->route('supplier.products.index')->with('success', __('admin.Success') ?? 'تمت الإضافة بنجاح');
    }

    public function edit(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }
        return view('website.profile.supplier.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $product->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'price' => $request->price,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $product->addMedia($file)
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->preservingOriginal()
                    ->toMediaCollection('product_images');
            }
        }

        return redirect()->route('supplier.products.index')->with('success', __('admin.Success') ?? 'تم التعديل بنجاح');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        $product->delete();
        return back()->with('success', __('admin.Success') ?? 'تم الحذف بنجاح');
    }

    public function destroyImage(Product $product, $mediaId)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        $media = $product->media()->findOrFail($mediaId);
        $media->delete();

        return response()->json(['success' => true]);
    }
}
