<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SuccessPartnerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SuccessPartnerRequest;
use App\Services\SuccessPartnerService;
use Illuminate\Http\Request;

class SuccessPartnerController extends Controller
{
    public function __construct(private SuccessPartnerService $partnerService) {}

    public function index(SuccessPartnerDataTable $dataTable)
    {
        return $dataTable->render('dashboard.success_partners.index');
    }

    public function create()
    {
        return view('dashboard.success_partners.create');
    }

    public function store(SuccessPartnerRequest $request)
    {
        $this->partnerService->create(
            $request->validated(),
            $request->file('image')
        );
        return redirect()->route('success-partners.index')->with('success', __('admin.save_success'));
    }

    public function show($id)
    {
        return redirect()->route('success-partners.index');
    }

    public function edit($id)
    {
        $partner = $this->partnerService->getById($id);
        return view('dashboard.success_partners.edit', compact('partner'));
    }

    public function update(SuccessPartnerRequest $request, $id)
    {
        $partner = $this->partnerService->getById($id);
        $this->partnerService->update($partner, $request->validated(), $request->file('image'));
        return redirect()->route('success-partners.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $this->partnerService->delete($id);
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
