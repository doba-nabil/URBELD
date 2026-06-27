<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionPackageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionPackageRequest;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    public function index(SubscriptionPackageDataTable $dataTable)
    {
        return $dataTable->render('dashboard.subscription_packages.index');
    }

    public function create()
    {
        return view('dashboard.subscription_packages.create');
    }

    public function store(SubscriptionPackageRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }
        
        SubscriptionPackage::create($data);
        return redirect()->route('subscription-packages.index')->with('success', __('admin.save_success'));
    }

    public function show($id)
    {
        return redirect()->route('subscription-packages.index');
    }

    public function edit($id)
    {
        $package = SubscriptionPackage::findOrFail($id);
        return view('dashboard.subscription_packages.edit', compact('package'));
    }

    public function update(SubscriptionPackageRequest $request, $id)
    {
        $package = SubscriptionPackage::findOrFail($id);
        $data = $request->validated();
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }
        
        $package->update($data);
        return redirect()->route('subscription-packages.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $package = SubscriptionPackage::findOrFail($id);
            $package->delete();
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
