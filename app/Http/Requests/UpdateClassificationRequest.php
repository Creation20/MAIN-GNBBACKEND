<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $classificationId = $this->route('classification') ? $this->route('classification')->id : null;
        
        return [
            'class_number' => 'sometimes|string|unique:classifications,class_number,'.$classificationId,
            'isbn' => 'nullable|string',
            'subject'=> 'nullable|string',
        ];
    }
}
