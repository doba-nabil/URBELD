<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SupplyRequestDataTable;
use App\Http\Controllers\Controller;
use App\Models\SupplyRequest;
use Illuminate\Http\Request;

class SupplyRequestController extends Controller
{
    public function index(SupplyRequestDataTable $dataTable)
    {
        return $dataTable->render('dashboard.supply_requests.index');
    }

    public function show($id)
    {
        $supplyRequest = SupplyRequest::with(['user', 'city', 'responses.user'])->find($id);
        if (!$supplyRequest) {
            \App\Models\Notification::where('link', 'like', "%/supply-requests/{$id}")->delete();
            return redirect()->route('admin.notifications.index')->with('error', 'هذا الطلب لم يعد موجوداً وتم حذف إشعاره.');
        }
        return view('dashboard.supply_requests.show', compact('supplyRequest'));
    }

    public function destroy($id)
    {
        try {
            $supplyRequest = SupplyRequest::findOrFail($id);
            $supplyRequest->delete();

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
