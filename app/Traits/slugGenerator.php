<?php
namespace App\Traits;
use Illuminate\Support\Str;
trait slugGenerator
{
    protected function generateSlug(array $data): string
    {
        if (isset($data['slug']) && !empty($data['slug'])) {
            return $data['slug'];
        }
        $name = $data['name']['ar'] ?? $data['name']['en'] ?? uniqid();
        return Str::slug($name);
    }
}
