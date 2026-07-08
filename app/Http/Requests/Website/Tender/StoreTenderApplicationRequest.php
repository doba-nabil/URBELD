<?php

namespace App\Http\Requests\Website\Tender;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Tender;

class StoreTenderApplicationRequest extends FormRequest
{
    public function authorize()
    {
        $tenderId = $this->route('id');
        $tender = Tender::findOrFail($tenderId);
        
        $user = auth()->user();
        
        // Prevent applying to own tender
        if ($tender->user_id === $user->id) {
            return false;
        }
        
        // Ensure user has active subscription or paid for this tender
        return $user->canApplyToTender($tender->id);
    }

    public function rules()
    {
        return [
            'price' => 'nullable|numeric|min:0',
            'delivery_days' => 'nullable|integer|min:1',
            'notes' => 'required|string',
            'files.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg,zip,doc,docx|max:10240',
            'file_titles.*' => 'nullable|string|max:255',
        ];
    }
}
