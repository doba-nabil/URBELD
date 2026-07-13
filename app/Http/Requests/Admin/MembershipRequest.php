<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MembershipRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->route('membership')],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . $this->route('membership')],
            'bio' => ['nullable', 'string'],
            'years_of_experience' => ['nullable', 'integer', 'min:0'],
            'type' => ['required', 'in:company,individual,supplier'],
            'is_active' => ['nullable', 'boolean'],
            'active' => ['nullable', 'in:active,pending,blocked'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'subscription_package_id' => ['nullable', 'exists:subscription_packages,id'],
            'subscription_start_at' => ['nullable', 'date'],
            'subscription_end_at' => ['nullable', 'date'],
            'password' => [$this->isMethod('POST') ? 'required' : 'nullable', 'confirmed', 'min:8'],
            'password_confirmation' => [$this->isMethod('POST') ? 'required' : 'nullable', 'min:8'],
            
            // For individual (engineer)
            'id_front_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'id_back_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            
            // For company
            'commercial_registration' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
            'employees_count' => ['nullable', 'integer', 'min:1'],
            'main_category_id' => ['nullable', 'exists:categories,id'],
            'sub_categories' => ['nullable', 'array'],
            'sub_categories.*' => ['exists:categories,id'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'delivery_cities' => ['nullable', 'array'],
            'delivery_cities.*' => ['exists:cities,id'],
            
            // Certificates
            'certificates' => ['nullable', 'array'],
            'certificates.*.id' => ['nullable', 'string'],
            'certificates.*.name' => ['required_with:certificates.*', 'string', 'max:255'],
            'certificates.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Works
            'works' => ['nullable', 'array'],
            'works.*.id' => ['nullable', 'integer'],
            'works.*.title' => ['required_with:works.*', 'string', 'max:255'],
            'works.*.description' => ['nullable', 'string'],
            'works.*.images' => ['nullable', 'array'],
            'works.*.images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            
            // Personal Photo
            'personal_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        return $rules;
    }
}
