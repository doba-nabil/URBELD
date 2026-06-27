<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequestResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_request_id' => ['sometimes', 'required', 'exists:service_requests,id'],
            'message' => ['required', 'string', 'max:1000'],
            'proposed_price' => ['required', 'numeric', 'min:0'],
            'proposed_timeline' => ['required', 'string', 'max:500'],
        ];
    }
}
