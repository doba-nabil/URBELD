<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CountryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Services\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct(private CountryService $countryService) {}


    public function index(CountryDataTable $dataTable)
    {
        return $dataTable->render('dashboard.countries.index');
    }

    public function create()
    {
        return view('dashboard.countries.create_edit');
    }

    public function store(CountryRequest $request)
    {
        $this->countryService->create($request->validated(), $request->file('image'));
        return redirect()->route('countries.index')->with('success', __('admin.save_success'));
    }

    public function edit($id)
    {
        $model = $this->countryService->getById($id);
        return view('dashboard.countries.create_edit', compact('model'));
    }

    public function update(CountryRequest $request, $id)
    {
        $model = $this->countryService->getById($id);
        $this->countryService->update($model, $request->validated(), $request->file('image'));
        return redirect()->route('countries.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $this->countryService->delete($id);

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

}
