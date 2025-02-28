<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrefixRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Możesz dodać uprawnienia, jeśli są potrzebne
    }

    public function rules()
    {
        return [
            'prefix' => 'required|string|min:2|max:50'
        ];
    }

    public function messages()
    {
        return [
            'prefix.required' => 'Prefix is required.',
            'prefix.string'   => 'Prefix must be a string.',
            'prefix.min'      => 'Prefix must be at least 2 characters.',
            'prefix.max'      => 'Prefix cannot be longer than 50 characters.'
        ];
    }
}
