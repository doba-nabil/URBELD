<?php

namespace App\Services;

use App\Models\City;

class CityService
{
    public function getAll()
    {
        return City::with('country')->get();
    }

    public function getById($id)
    {
        return City::with('country')->findOrFail($id);
    }

    public function create(array $data)
    {
        return City::create($data);
    }

    public function update(City $model, array $data)
    {
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = City::findOrFail($id);
        return $model->delete();
    }
}
