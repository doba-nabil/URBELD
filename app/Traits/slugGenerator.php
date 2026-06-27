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
        
        // استخدام الاسم العربي أولاً، ثم الإنجليزي إذا كان موجوداً
        $name = $data['name']['ar'] ?? $data['name']['en'] ?? uniqid();
        
        // تحويل النص العربي إلى slug
        return Str::slug($name);
    }
}
