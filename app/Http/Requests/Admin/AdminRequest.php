<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
        $userId = $this->route('admin');

        return [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$userId}",
            'phone' => 'nullable|string|max:20',
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
            'password' => $this->isMethod('POST') ? 'required|string|min:6|confirmed' : 'nullable|string|min:6|confirmed',
            'receive_email_notifications' => 'nullable|boolean',
            'receive_push_notifications' => 'nullable|boolean',
        ];
    }
}
