<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class ServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $categoryId = $this->input('category_id');
        $rules = [
            'user_id' => ['nullable', 'exists:users,id'],
            'provider_id' => ['nullable', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['nullable', 'exists:categories,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'property_type' => ['nullable', 'in:residential,commercial,industrial,other'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'string', 'max:50'],
            'longitude' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:under_review,pending,provider_accepted,seeker_confirmed_provider,inspection_scheduled,inspection_done,work_completed,completed,time_expired,cancelled,rejected_by_user'],
        ];
        if ($categoryId) {
            $category = \App\Models\Category::find($categoryId);
            if ($category) {
                if ($category->slug === 'contracting') {
                    $rules['blueprint_description'] = ['nullable', 'string'];
                    $rules['blueprints'] = ['nullable', 'array'];
                    $rules['blueprints.*'] = ['file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'];
                }
                if ($category->slug === 'engineering-consulting') {
                    $rules['site_photos_description'] = ['nullable', 'string'];
                    $rules['site_photos'] = ['nullable', 'array'];
                    $rules['site_photos.*'] = ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
                }
                if ($category->slug === 'environment') {
                    $rules['activity_type_id'] = ['nullable', 'exists:activity_types,id'];
                    $rules['neighbors_description'] = ['nullable', 'string'];
                }
            }
        }
        return $rules;
    }
}
