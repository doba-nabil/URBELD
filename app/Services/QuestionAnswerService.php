<?php

namespace App\Services;

use App\Models\QusetionAnswer;
use App\Traits\mediaUploader;
use App\Traits\slugGenerator;

class QuestionAnswerService
{
//    use mediaUploader, slugGenerator;

    public function getAll()
    {
        return QusetionAnswer::all();
    }

    public function getById($id)
    {
        return QusetionAnswer::findOrFail($id);
    }

    public function create(array $data)
    {
        $model = QusetionAnswer::create($data);
        return $model;
    }

    public function update(QusetionAnswer $model, array $data)
    {
        $model->update($data);
        return $model;
    }


    public function delete($id)
    {
        $model = QusetionAnswer::findOrFail($id);
        return $model->delete();
    }
}
