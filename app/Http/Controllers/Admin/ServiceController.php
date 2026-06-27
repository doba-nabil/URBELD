<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ServiceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest as ServiceFormRequest;
use App\Models\Category;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private ServiceService $serviceService) {}

    public function index(ServiceDataTable $dataTable)
    {
        return $dataTable->render('dashboard.services.index');
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $providers = \App\Models\User::serviceProviders()->get();
        return view('dashboard.services.create', compact('categories', 'providers'));
    }

    public function store(ServiceFormRequest $request)
    {
        $this->serviceService->create(
            $request->validated(),
            $request->file('image')
        );
        return redirect()->route('services.index')->with('success', __('admin.save_success'));
    }

    public function show($id)
    {
        $service = $this->serviceService->getById($id);
        return view('dashboard.services.show', compact('service'));
    }

    public function edit($id)
    {
        $service = $this->serviceService->getById($id);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $providers = \App\Models\User::serviceProviders()->get();
        return view('dashboard.services.edit', compact('service', 'categories', 'providers'));
    }

    public function update(ServiceFormRequest $request, $id)
    {
        $service = $this->serviceService->getById($id);
        $this->serviceService->update($service, $request->validated(), $request->file('image'));
        return redirect()->route('services.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $this->serviceService->delete($id);
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
