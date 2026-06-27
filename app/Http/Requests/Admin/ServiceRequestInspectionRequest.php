<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequestInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_request_id' => ['required', 'exists:service_requests,id'],
            'response_id' => ['required', 'exists:service_request_responses,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_request_id.required' => __('admin.request_id_required'),
            'service_request_id.exists' => __('admin.request_id_exists'),
            'response_id.required' => __('admin.response_id_required'),
            'response_id.exists' => __('admin.response_id_exists'),
            'scheduled_at.required' => __('admin.scheduled_at_required'),
            'scheduled_at.date' => __('admin.scheduled_at_date'),
            'scheduled_at.after' => __('admin.scheduled_at_after'),
        ];
    }
}
