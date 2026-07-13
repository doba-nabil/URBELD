<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyClassification;
use App\DataTables\CompanyClassificationDataTable;
use Illuminate\Http\Request;

class CompanyClassificationController extends Controller
{
    public function index(CompanyClassificationDataTable $dataTable)
    {
        return $dataTable->render('dashboard.company_classifications.index');
    }

    public function create()
    {
        return view('dashboard.company_classifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.ar' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'type' => 'required|in:company,supplier',
        ]);

        CompanyClassification::create($request->all());

        return redirect()->route('company_classifications.index')->with('success', __('admin.save_success') ?? 'تمت الإضافة بنجاح');
    }

    public function edit(CompanyClassification $companyClassification)
    {
        return view('dashboard.company_classifications.edit', compact('companyClassification'));
    }

    public function update(Request $request, CompanyClassification $companyClassification)
    {
        $request->validate([
            'name.ar' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'type' => 'required|in:company,supplier',
        ]);

        $companyClassification->update($request->all());

        return redirect()->route('company_classifications.index')->with('success', __('admin.update_success') ?? 'تم التعديل بنجاح');
    }

    public function destroy(CompanyClassification $companyClassification)
    {
        $companyClassification->delete();
        return redirect()->route('company_classifications.index')->with('success', __('admin.delete_success') ?? 'تم الحذف بنجاح');
    }
}
