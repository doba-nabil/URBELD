<?php

namespace App\Services;

use App\Models\Service;
use App\Traits\mediaUploader;

class ServiceService
{
    use mediaUploader;

    public function getAll()
    {
        return Service::ordered()->get();
    }

    public function getById($id)
    {
        return Service::findOrFail($id);
    }

    public function create(array $data, $image = null)
    {
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $service = Service::create($data);
        
        if ($image) {
            $this->handleImage($service, $image, false, 'services');
        }

        return $service;
    }

    public function update(Service $service, array $data, $image = null)
    {
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $service->update($data);
        
        if ($image) {
            $this->handleImage($service, $image, true, 'services');
        }

        return $service;
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        return $service->delete();
    }
}
