<?php

namespace App\Http\Requests\Website\Tender;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenderRequest extends FormRequest
{
    public function authorize()
    {
        // Auth check happens in middleware, here we verify specific permissions
        return auth()->check() && auth()->user()->canPostTender();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_type' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'budget' => 'nullable|numeric|min:0',
            'qualification_requirements' => 'nullable|array',
            'qualification_requirements.*' => 'string|max:255',
            'ends_at' => 'required|date|after:today',
            'is_urgent' => 'boolean',
            'files.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg,zip,doc,docx|max:10240',
            'file_titles.*' => 'nullable|string|max:255',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'يرجى إدخال عنوان المناقصة',
            'category_id.required' => 'يرجى اختيار التصنيف',
            'city_id.required' => 'يرجى اختيار المدينة',
            'ends_at.required' => 'تاريخ الانتهاء مطلوب',
            'ends_at.after' => 'تاريخ الانتهاء يجب أن يكون في المستقبل',
        ];
    }
}
