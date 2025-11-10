<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $stockId = $this->route('stock') ? $this->route('stock')->id : null;
        
        return [
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => 'nullable|string|unique:stocks,isbn,'.$stockId,
            'classification_id' => 'nullable|exists:classifications,id',
            'is_gnb_stock' => 'boolean',
        ];
    }
}
