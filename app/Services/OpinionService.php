<?php

namespace App\Services;

use App\Models\Opinion;
use App\Traits\mediaUploader;

class OpinionService
{
    use mediaUploader;

    public function getAll()
    {
        return Opinion::all();
    }

    public function getById($id)
    {
        return Opinion::findOrFail($id);
    }

    public function create(array $data, $image = null)
    {
        $opinion = Opinion::create($data);
        $this->handleImage($opinion, $image, false, 'opinions');
        return $opinion;
    }

    public function update(Opinion $opinion, array $data, $image = null)
    {
        $opinion->update($data);
        $this->handleImage($opinion, $image, true, 'opinions');
        return $opinion;
    }

    public function delete($id)
    {
        $opinion = Opinion::findOrFail($id);
        return $opinion->delete();
    }
}

