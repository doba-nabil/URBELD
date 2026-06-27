<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'array'],
            'name.ar' => ['required', 'string', 'max:255'],
            'badge_name' => ['nullable', 'array'],
            'badge_name.ar' => ['nullable', 'string', 'max:255'],
            'badge_name.en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'max_services' => ['nullable', 'integer', 'min:0'],
            'works_limit' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:10'],
        ];

        if (count(\LaravelLocalization::getSupportedLocales()) > 1 && isset(\LaravelLocalization::getSupportedLocales()['en'])) {
            $rules['name.en'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}
