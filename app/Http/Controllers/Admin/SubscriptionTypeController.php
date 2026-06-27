<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionTypeRequest;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class SubscriptionTypeController extends Controller
{
    public function index(SubscriptionTypeDataTable $dataTable)
    {
        return $dataTable->render('dashboard.subscription_types.index');
    }

    public function create()
    {
        return view('dashboard.subscription_types.create');
    }

    public function store(SubscriptionTypeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        
        SubscriptionType::create($data);
        return redirect()->route('subscription-types.index')->with('success', __('admin.save_success'));
    }

    public function show($id)
    {
        return redirect()->route('subscription-types.index');
    }

    public function edit($id)
    {
        $type = SubscriptionType::findOrFail($id);
        return view('dashboard.subscription_types.edit', compact('type'));
    }

    public function update(SubscriptionTypeRequest $request, $id)
    {
        $type = SubscriptionType::findOrFail($id);
        $data = $request->validated();
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        
        $type->update($data);
        return redirect()->route('subscription-types.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $type = SubscriptionType::findOrFail($id);
            $type->delete();
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
