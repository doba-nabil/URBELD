<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(\App\DataTables\RegionDataTable $dataTable)
    {
        return $dataTable->render('dashboard.regions.index');
    }

    public function create()
    {
        $countries = \App\Models\Country::all();
        return view('dashboard.regions.create_edit', compact('countries'));
    }

    public function store(\App\Http\Requests\Admin\RegionRequest $request)
    {
        \App\Models\Region::create($request->validated());
        return redirect()->route('regions.index')->with('success', __('admin.save_success'));
    }

    public function edit($id)
    {
        $model = \App\Models\Region::findOrFail($id);
        $countries = \App\Models\Country::all();
        return view('dashboard.regions.create_edit', compact('model', 'countries'));
    }

    public function update(\App\Http\Requests\Admin\RegionRequest $request, $id)
    {
        $model = \App\Models\Region::findOrFail($id);
        $model->update($request->validated());
        return redirect()->route('regions.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $model = \App\Models\Region::findOrFail($id);
            $model->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('admin.delete_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('admin.delete_error')
            ], 500);
        }
    }

    public function getByCountry($countryId)
    {
        $regions = \App\Models\Region::where('country_id', $countryId)->get();
        
        return response()->json([
            'status' => 'success',
            'regions' => $regions->map(function ($region) {
                $name = $region->name;
                if (is_array($name)) {
                    $locale = app()->getLocale();
                    $name = $name[$locale] ?? $name['ar'] ?? $name['en'] ?? '-';
                }
                return [
                    'id' => $region->id,
                    'name' => $name,
                ];
            })
        ]);
    }
