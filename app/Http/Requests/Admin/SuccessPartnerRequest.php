<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SuccessPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'array'],
            'title.ar' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if (count(\LaravelLocalization::getSupportedLocales()) > 1 && isset(\LaravelLocalization::getSupportedLocales()['en'])) {
            $rules['title.en'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}
