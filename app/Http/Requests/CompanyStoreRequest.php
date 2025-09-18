<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:1000',
            'industry' => 'nullable|string|max:255',
        ];
    }
}
