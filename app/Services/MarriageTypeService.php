<?php

namespace App\Services;

use App\Models\MarriageType;
use App\Traits\mediaUploader;
use App\Traits\slugGenerator;

class MarriageTypeService
{
//    use mediaUploader, slugGenerator;

    public function getAll()
    {
        return MarriageType::all();
    }

    public function getById($id)
    {
        return MarriageType::findOrFail($id);
    }

    public function create(array $data)
    {
        $model = MarriageType::create($data);
        return $model;
    }

    public function update(MarriageType $model, array $data)
    {
        $model->update($data);
        return $model;
    }


    public function delete($id)
    {
        $model = MarriageType::findOrFail($id);
        return $model->delete();
    }
}
