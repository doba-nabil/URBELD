<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LocationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LocationRequest;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private LocationService $locationService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->is('*states*')){
            $type = 'state';
            $url = 'states';
        }
        if(request()->is('*countries*')){
            $type = 'country';
            $url = 'countries';
        }
        if(request()->is('*cities*')){
            $type = 'city';
            $url = 'cities';
        }
        $dataTable = new LocationDataTable($type);
        return $dataTable->with('type', $type)->render('dashboard.locations.index', compact('type', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(request()->is('*states*')){
            $type = 'states';
            $type_request = 'state';
        }
        if(request()->is('*countries*')){
            $type = 'countries';
            $type_request = 'country';
        }
        if(request()->is('*cities*')){
            $type = 'cities';
            $type_request = 'city';
        }
        $countries = Location::whereNull('parent_id')->get();
        return view('dashboard.locations.create',compact('type','type_request', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest $request)
    {
        if(request()->is('*countries*')){
            $message = 'success_create_country';
            $route = 'countries';
        }
        if(request()->is('*states*')){
            $message = 'success_create_state';
            $route = 'states';
        }
        if(request()->is('*cities*')){
            $message = 'success_create_city';
            $route = 'cities';
        }
        $this->locationService->create($request->validated());
        return redirect()->route($route.'.index')->with('success', __('admin.'.$message));
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $location = $this->locationService->getById($id);
        if($location->type == 'state'){
            $type = 'states';
        }
        if($location->type == 'country'){
            $type = 'countries';
        }
        if($location->type == 'city'){
            $type = 'cities';
        }
        $countries = Location::whereNull('parent_id')->get();
        return view('dashboard.locations.edit', compact('location', 'type', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest $request, $id)
    {
        $location = $this->locationService->getById($id);
        $this->locationService->update($location, $request->validated());
        if($location->type == 'country'){
            $message = 'success_update_country';
            $route = 'countries';
        }
        if($location->type == 'state'){
            $message = 'success_update_state';
            $route = 'states';
        }
        if($location->type == 'city'){
            $message = 'success_update_city';
            $route = 'cities';
        }
        return redirect()->route($route.'.index')->with('success', __('admin.'.$message));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->locationService->delete($id);
            return response()->json([
                'status' => 'success',
                'message' => __('admin.success_deleted')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('admin.error_deleted')
            ], 500);
        }
    }


    public function getParents(Request $request)
    {
        $type = $request->get('type');
        $countryId = $request->get('country_id');
        $stateId = $request->get('state_id');
        $locale = app()->getLocale();

        if ($type == 'state' && $countryId) {
            $parents = Location::where('type', 'state')
                ->where('parent_id', $countryId)
                ->get(['id', 'name'])
                ->map(function ($state) use ($locale) {
                    return [
                        'id' => $state->id,
                        'name' => $state->getTranslation('name', $locale)
                    ];
                });
        } elseif ($type == 'city' && $stateId) {
            $parents = Location::where('type', 'city')
                ->where('parent_id', $stateId)
                ->get(['id', 'name'])
                ->map(function ($city) use ($locale) {
                    return [
                        'id' => $city->id,
                        'name' => $city->getTranslation('name', $locale)
                    ];
                });
        } else {
            $parents = [];
        }

        return response()->json($parents);
    }
}
