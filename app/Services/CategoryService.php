<?php

namespace App\Services;

use App\Models\Category;
use App\Traits\mediaUploader;
use App\Traits\slugGenerator;

class CategoryService
{
    use mediaUploader, slugGenerator;

    public function getAll()
    {
        return Category::all();
    }

    public function getParents($excludeId = null)
    {
        $query = Category::whereNull('parent_id');
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->get();
    }

    public function getById($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data, $image = null)
    {
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        $data['slug'] = $this->generateSlug($data);
        $category = Category::create($data);
        $this->handleImage($category, $image, false,'categories');
        return $category;
    }

    public function update(Category $category, array $data, $image = null)
    {
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        $data['slug'] = $this->generateSlug($data);
        $category->update($data);
        $this->handleImage($category, $image, true, 'categories');
        return $category;
    }


    public function delete($id)
    {
        $category = Category::findOrFail($id);
        return $category->delete();
    }
}
