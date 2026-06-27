<?php

namespace App\Services;

use App\Models\SuccessPartner;
use App\Traits\mediaUploader;

class SuccessPartnerService
{
    use mediaUploader;

    public function getAll()
    {
        return SuccessPartner::ordered()->get();
    }

    public function getById($id)
    {
        return SuccessPartner::findOrFail($id);
    }

    public function create(array $data, $image = null)
    {
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $partner = SuccessPartner::create($data);
        
        if ($image) {
            $this->handleImage($partner, $image, false, 'partners');
        }

        return $partner;
    }

    public function update(SuccessPartner $partner, array $data, $image = null)
    {
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1';
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $partner->update($data);
        
        if ($image) {
            $this->handleImage($partner, $image, true, 'partners');
        }

        return $partner;
    }

    public function delete($id)
    {
        $partner = SuccessPartner::findOrFail($id);
        return $partner->delete();
    }
}
