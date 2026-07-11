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
        $data['show_in_home'] = isset($data['show_in_home']) && $data['show_in_home'] == '1';
        $data['supports_tenders'] = isset($data['supports_tenders']) && $data['supports_tenders'] == '1';
        $data['is_full_width'] = isset($data['is_full_width']) && $data['is_full_width'] == '1';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        $data['slug'] = $this->generateSlug($data);
        $category = Category::create($data);
        $this->handleImage($category, $image, false,'categories');
        return $category;
    }

    public function update(Category $category, array $data, $image = null)
    {
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        $data['show_in_home'] = isset($data['show_in_home']) && $data['show_in_home'] == '1';
        $data['supports_tenders'] = isset($data['supports_tenders']) && $data['supports_tenders'] == '1';
        $data['is_full_width'] = isset($data['is_full_width']) && $data['is_full_width'] == '1';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
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
