<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string',
            'class_number' => 'required|string|unique:classifications',
            'isbn' => 'nullable|string',
            'subject'=> 'nullable|string',
        ];
    }
}
