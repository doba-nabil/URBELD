<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ActivityTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActivityTypeRequest;
use App\Services\ActivityTypeService;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    public function __construct(private ActivityTypeService $activityTypeService) {}

    public function index(ActivityTypeDataTable $dataTable)
    {
        return $dataTable->render('dashboard.activity_types.index');
    }

    public function create()
    {
        return view('dashboard.activity_types.create');
    }

    public function store(ActivityTypeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';

        $this->activityTypeService->create($data);
        return redirect()->route('activity-types.index')->with('success', __('admin.save_success'));
    }

    public function edit($id)
    {
        $activityType = $this->activityTypeService->getById($id);
        return view('dashboard.activity_types.edit', compact('activityType'));
    }

    public function update(ActivityTypeRequest $request, $id)
    {
        $activityType = $this->activityTypeService->getById($id);
        $data = $request->validated();
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';

        $this->activityTypeService->update($activityType, $data);
        return redirect()->route('activity-types.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $this->activityTypeService->delete($id);

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
