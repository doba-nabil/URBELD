<?php

namespace App\Http\Controllers\Website\Supplier;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class DeliveryCityController extends Controller
{
    public function index()
    {
        $allCities = City::where('is_active', true)->get();
        $selectedCities = auth()->user()->deliveryCities()->pluck('cities.id')->toArray();
        return view('website.profile.supplier.cities.index', compact('allCities', 'selectedCities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cities' => 'nullable|array',
            'cities.*' => 'exists:cities,id',
        ]);

        auth()->user()->deliveryCities()->sync($request->cities ?? []);

        return back()->with('success', __('admin.Success') ?? 'تم تحديث مدن التوصيل بنجاح');
    }
}
