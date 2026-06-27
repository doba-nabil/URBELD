<?php

namespace App\Services;

use App\Models\QuestionCategory;
use App\Models\Question;
use App\Models\QuestionAnswer as Answer;
use App\Traits\mediaUploader;
use App\Traits\slugGenerator;

class QuestionService
{
//    use mediaUploader, slugGenerator;

    public function getAll()
    {
        return Question::all();
    }

    public function getById($id)
    {
        return Question::findOrFail($id);
    }

    public function create(array $data)
    {
        $category_name = QuestionCategory::find($data['question_category_id']);
        $data['question_category'] = $category_name->name;
        $model = Question::create($data);
        if (!empty($data['is_select']) && !empty($data['answers'])) {
            foreach ($data['answers'] as $answer) {
                if ($answer) {
                    $model->answers()->create(['answer' => $answer]);
                }
            }
        }

        return $model;
    }

    public function update(Question $model, array $data)
    {
        $category_name = QuestionCategory::find($data['question_category_id']);
        $data['question_category'] = $category_name->name;
        $model->update($data);
        $model->answers()->delete();
        if (!empty($data['is_select'])) {
            if (!empty($data['answers'])) {
                foreach ($data['answers'] as $answer) {
                    if ($answer) {
                        $model->answers()->create(['answer' => $answer]);
                    }
                }
            }
        }

        return $model;
    }


    public function delete($id)
    {
        $model = Question::findOrFail($id);
        return $model->delete();
    }
}
