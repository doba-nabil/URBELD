<?php

namespace App\Services;

use App\Models\Banner;
use App\Traits\mediaUploader;

class BannerService
{
    use mediaUploader;

    public function getAll()
    {
        return Banner::orderBy('order')->get();
    }

    public function getActiveBanners($page = 'home', $position = null, $limit = 4)
    {
        $query = Banner::where('is_active', true)
            ->where(function($q) use ($page) {
                $q->where('page', $page)
                  ->orWhere('page', 'all');
            });
        
        if ($position) {
            $query->where('position', $position);
        }
        
        return $query->orderBy('order')
            ->limit($limit)
            ->get();
    }

    public function getById($id)
    {
        return Banner::findOrFail($id);
    }

    public function create(array $data, $image = null)
    {
        $banner = Banner::create($data);
        $this->handleImage($banner, $image, false, 'banners');
        return $banner;
    }

    public function update(Banner $banner, array $data, $image = null)
    {
        $banner->update($data);
        $this->handleImage($banner, $image, true, 'banners');
        return $banner;
    }

    public function delete($id)
    {
        $banner = Banner::findOrFail($id);
        return $banner->delete();
    }
}

