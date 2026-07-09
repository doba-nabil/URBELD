<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = \App\Models\User::where('provider_type', 'supplier')
            ->latest()
            ->paginate(15);
            
        return view('dashboard.suppliers.index', compact('suppliers'));
    }

    public function show(string $id)
    {
        $supplier = \App\Models\User::where('provider_type', 'supplier')->findOrFail($id);
        $products = $supplier->products()->latest()->paginate(10, ['*'], 'products_page');
        $offers = $supplier->supplierOffers()->latest()->paginate(10, ['*'], 'offers_page');
        
        return view('dashboard.suppliers.show', compact('supplier', 'products', 'offers'));
    }
}
