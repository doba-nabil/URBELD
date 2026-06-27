<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AddonDataTable;
use App\DataTables\PageDataTable;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\mediaUploader;
use App\Traits\slugGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    use mediaUploader, slugGenerator;

    public function index(PageDataTable $dataTable)
    {
        return $dataTable->render('dashboard.pages.index');
    }

    public function create()
    {
        return view('dashboard.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title.ar' => 'required|string|max:255',
            'type' => 'required|in:content,link',
            'content.ar' => 'nullable|string|required_if:type,content',
            'target_url' => 'nullable|string|required_if:type,link',
        ]);
        
        $data = $request->all();
        $data['slug'] = \Illuminate\Support\Str::slug($request->title['ar']);
        $page = Page::create($data);
        $this->handleImage($page, $request->page_video, false, 'page_video');
        Cache::forget('footer_pages_links');
        Cache::forget('footer_pages_content');
        return redirect()->route('pages.index')->with('success', __('admin.save_success'));
    }

    public function show(Page $page)
    {
        return redirect()->route('pages.edit', $page->id);
    }

    public function edit(Page $page)
    {
        return view('dashboard.pages.edit', compact('page'));
    }

    public function delete_video($id)
    {
        $page = Page::findOrFail($id);
        $page->clearMediaCollection('page_video');
        return redirect()->back()->with('success', __('admin.delete_success'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title.ar' => 'required|string|max:255',
            'type' => 'required|in:content,link',
            'content.ar' => 'nullable|string|required_if:type,content',
            'target_url' => 'nullable|string|required_if:type,link',
        ]);
        $data = $request->all();
        if($page->slug != 'about-us'){
            $data['slug'] = \Illuminate\Support\Str::slug($request->title['ar']);
        }
        $page->update($data);
        $this->handleImage($page, $request->page_video, true, 'page_video');
        Cache::forget('footer_pages_links');
        Cache::forget('footer_pages_content');
        return redirect()->route('pages.index')->with('success', __('admin.update_success'));
    }

    public function destroy(Page $page)
    {
        try {
            $page->delete();
            Cache::forget('footer_pages_links');
            Cache::forget('footer_pages_content');
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

