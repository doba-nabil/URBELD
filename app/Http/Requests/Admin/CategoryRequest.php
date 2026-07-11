<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class CategoryRequest extends FormRequest
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
            'description' => ['nullable', 'array'],
            'description.ar' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'icon' => ['nullable', 'string', 'max:255'],
            'icon_title' => ['nullable', 'array'],
            'icon_title.ar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'color' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'show_in_home' => ['nullable', 'boolean'],
            'supports_tenders' => ['nullable', 'boolean'],
            'supports_supply_requests' => ['nullable', 'boolean'],
            'is_full_width' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'bulk_request_title' => ['nullable', 'array'],
            'bulk_request_title.ar' => ['nullable', 'string', 'max:255'],
            'bulk_request_subtitle' => ['nullable', 'array'],
            'bulk_request_subtitle.ar' => ['nullable', 'string', 'max:500'],
            'bulk_request_button_text' => ['nullable', 'array'],
            'bulk_request_button_text.ar' => ['nullable', 'string', 'max:255'],
        ];
        if (count(\LaravelLocalization::getSupportedLocales()) > 1 && isset(\LaravelLocalization::getSupportedLocales()['en'])) {
            $rules['name.en'] = ['nullable', 'string', 'max:255'];
            $rules['description.en'] = ['nullable', 'string'];
            $rules['icon_title.en'] = ['nullable', 'string', 'max:255'];
            $rules['bulk_request_title.en'] = ['nullable', 'string', 'max:255'];
            $rules['bulk_request_subtitle.en'] = ['nullable', 'string', 'max:500'];
            $rules['bulk_request_button_text.en'] = ['nullable', 'string', 'max:255'];
        }
        return $rules;
    }
}
