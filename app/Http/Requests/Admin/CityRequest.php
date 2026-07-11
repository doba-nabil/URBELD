<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'array'],
            'name.ar' => ['required', 'string', 'max:255'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'region_id' => ['nullable', 'exists:regions,id'],
        ];

        if (count(\LaravelLocalization::getSupportedLocales()) > 1 && isset(\LaravelLocalization::getSupportedLocales()['en'])) {
            $rules['name.en'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}
