<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|nullable|string|max:1000',
            'industry' => 'sometimes|nullable|string|max:255',
        ];
    }
}
