<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RoleRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        // Create name from display_name.en if exists, otherwise from display_name.ar
        if ($this->filled('display_name.en')) {
            $this->merge([
                'name' => Str::slug($this->input('display_name.en')),
            ]);
        } elseif ($this->filled('display_name.ar')) {
            $this->merge([
                'name' => Str::slug($this->input('display_name.ar')),
            ]);
        }
    }

    public function rules(): array
    {
        $roleId = $this->route('role');

        return [
            'display_name' => ['required', 'array'],
            'display_name.ar' => ['required', 'string', 'min:3', 'max:255'],
            'display_name.en' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
            'name' => [
                'required',
                'string',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'display_name.required' => __('admin.role_name_required'),
            'display_name.array' => __('admin.role_name_array'),
            'display_name.ar.required' => __('admin.role_name_ar_required'),
            'display_name.ar.string' => __('admin.role_name_ar_string'),
            'display_name.ar.min' => __('admin.role_name_ar_min'),
            'display_name.ar.max' => __('admin.role_name_ar_max'),
            'name.required' => __('admin.role_slug_required'),
            'name.unique' => __('admin.role_slug_unique'),
        ];
    }

}
