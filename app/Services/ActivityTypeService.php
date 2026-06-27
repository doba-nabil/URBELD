<?php

namespace App\Services;

use App\Models\ActivityType;
use App\Traits\slugGenerator;

class ActivityTypeService
{
    use slugGenerator;

    public function getAll()
    {
        return ActivityType::ordered()->get();
    }

    public function getActive()
    {
        return ActivityType::active()->ordered()->get();
    }

    public function getById($id)
    {
        return ActivityType::findOrFail($id);
    }

    public function create(array $data)
    {
        return ActivityType::create($data);
    }

    public function update(ActivityType $activityType, array $data)
    {
        $activityType->update($data);
        return $activityType;
    }

    public function delete($id)
    {
        $activityType = ActivityType::findOrFail($id);
        return $activityType->delete();
    }
}
