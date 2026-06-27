<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}


    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('dashboard.categories.index');
    }

    public function create()
    {
        $parents = $this->categoryService->getParents();
        return view('dashboard.categories.create', compact('parents'));
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryService->create(
            $request->validated(),
            $request->file('image')
        );
        return redirect()->route('categories.index')->with('success', __('admin.save_success'));
    }

    public function show($id)
    {
        return redirect()->route('categories.edit', $id);
    }

    public function edit($id)
    {
        $category = $this->categoryService->getById($id);
        $parents = $this->categoryService->getParents($id);
        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = $this->categoryService->getById($id);
        $this->categoryService->update($category, $request->validated(), $request->file('image'));
        return redirect()->route('categories.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->delete($id);

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

    public function getChildren($id)
    {
        $category = $this->categoryService->getById($id);
        $children = $category->children()->with('parent')->get();
        
        return response()->json([
            'status' => 'success',
            'children' => $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'parent_id' => $child->parent_id,
                    'icon' => $child->icon,
                    'image' => $child->getFirstMediaUrl('categories'),
                    'children_count' => $child->children()->count(),
                    'edit_url' => route('categories.edit', $child->id),
                    'delete_url' => route('categories.destroy', $child->id),
                ];
            })
        ]);
    }

}
