<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FaqDataTable;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FaqController extends Controller
{
    public function index(FaqDataTable $dataTable)
    {
        return $dataTable->render('dashboard.faqs.index');
    }

    public function create()
    {
        return view('dashboard.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question.ar' => 'required|string',
            'answer.ar' => 'required|string',
            'is_active' => 'required|boolean',
            'sort_order' => 'required|integer',
        ]);

        Faq::create($request->all());
        Cache::forget('faqs');

        return redirect()->route('faqs.index')->with('success', __('admin.save_success'));
    }

    public function show(Faq $faq)
    {
        return redirect()->route('faqs.edit', $faq->id);
    }

    public function edit(Faq $faq)
    {
        return view('dashboard.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question.ar' => 'required|string',
            'answer.ar' => 'required|string',
            'is_active' => 'required|boolean',
            'sort_order' => 'required|integer',
        ]);

        $faq->update($request->all());
        Cache::forget('faqs');

        return redirect()->route('faqs.index')->with('success', __('admin.update_success'));
    }

    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();
            Cache::forget('faqs');
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
