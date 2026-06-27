<?php

namespace App\Services;

use App\Models\QuestionCategory;
use App\Traits\mediaUploader;
use App\Traits\slugGenerator;

class QuestionCategoryService
{
//    use mediaUploader, slugGenerator;

    public function getAll()
    {
        return QuestionCategory::all();
    }

    public function getById($id)
    {
        return QuestionCategory::findOrFail($id);
    }

    public function create(array $data)
    {
        $model = QuestionCategory::create($data);
        return $model;
    }

    public function update(QuestionCategory $model, array $data)
    {
        $model->update($data);
        return $model;
    }


    public function delete($id)
    {
        $model = QuestionCategory::findOrFail($id);
        return $model->delete();
    }
}
