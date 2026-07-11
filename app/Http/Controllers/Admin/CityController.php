<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CityDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\Models\Country;
use App\Services\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(private CityService $cityService) {}

    public function index(CityDataTable $dataTable)
    {
        return $dataTable->render('dashboard.cities.index');
    }

    public function create()
    {
        $countries = Country::all();
        $regions = \App\Models\Region::all();
        return view('dashboard.cities.create_edit', compact('countries', 'regions'));
    }

    public function store(CityRequest $request)
    {
        $this->cityService->create($request->validated());
        return redirect()->route('cities.index')->with('success', __('admin.save_success'));
    }

    public function edit($id)
    {
        $model = $this->cityService->getById($id);
        $countries = Country::all();
        $regions = \App\Models\Region::all();
        return view('dashboard.cities.create_edit', compact('model', 'countries', 'regions'));
    }

    public function update(CityRequest $request, $id)
    {
        $model = $this->cityService->getById($id);
        $this->cityService->update($model, $request->validated());
        return redirect()->route('cities.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $this->cityService->delete($id);

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
        $cities = \App\Models\City::where('country_id', $countryId)->get();
        
        return response()->json([
            'status' => 'success',
            'cities' => $cities->map(function ($city) {
                $name = $city->name;
                if (is_array($name)) {
                    $locale = app()->getLocale();
                    $name = $name[$locale] ?? $name['ar'] ?? $name['en'] ?? '-';
                }
                return [
                    'id' => $city->id,
                    'name' => $name,
                ];
            })
        ]);
    }
}
