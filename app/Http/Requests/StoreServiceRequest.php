<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['required', 'exists:categories,id'],
            'provider_id' => ['nullable', 'exists:users,id', 'not_in:' . auth()->id()],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string'],
            'dynamic_data' => ['nullable', 'array'],
            'city_id' => ['required', 'exists:cities,id'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'voice_record' => ['nullable', 'file', 'mimes:mp3,wav,ogg,webm', 'max:10240'],
            'service_id' => ['nullable', 'exists:services,id'],
            'attachment_link' => ['nullable', 'url', 'max:500'],
        ];

        return $rules;
    }
}
