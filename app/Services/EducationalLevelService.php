<?php

namespace App\Services;

use App\Models\EducationalLevel;
use App\Traits\mediaUploader;
use App\Traits\slugGenerator;

class EducationalLevelService
{
//    use mediaUploader, slugGenerator;

    public function getAll()
    {
        return EducationalLevel::all();
    }

    public function getById($id)
    {
        return EducationalLevel::findOrFail($id);
    }

    public function create(array $data)
    {
        $model = EducationalLevel::create($data);
        return $model;
    }

    public function update(EducationalLevel $model, array $data)
    {
        $model->update($data);
        return $model;
    }


    public function delete($id)
    {
        $model = EducationalLevel::findOrFail($id);
        return $model->delete();
    }
}
