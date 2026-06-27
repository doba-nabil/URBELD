<?php

namespace App\Services;

use App\Models\Country;
use App\Traits\mediaUploader;
use App\Traits\slugGenerator;

class CountryService
{
    use mediaUploader, slugGenerator;

    public function getAll()
    {
        return Country::all();
    }

    public function getById($id)
    {
        return Country::findOrFail($id);
    }

    public function create(array $data, $image = null)
    {
        $model = Country::create($data);
        $this->handleImage($model, $image, false,'countries');
        return $model;
    }

    public function update(Country $model, array $data, $image = null)
    {
        $model->update($data);
        $this->handleImage($model, $image, true, 'countries');
        return $model;
    }


    public function delete($id)
    {
        $model = Country::findOrFail($id);
        return $model->delete();
    }
}
