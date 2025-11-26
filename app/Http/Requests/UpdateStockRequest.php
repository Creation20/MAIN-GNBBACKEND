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

        $rules = [
            'date' => 'sometimes|date',
            'vendor' => 'sometimes|string|in:legal-deposit,donation,purchase',
            'matForm' => 'sometimes|string|in:Hardcopy,Softcopy,Audio,Hardcopy & Softcopy,Hardcopy & Audio,Softcopy & Audio,Hardcopy/Softcopy/Audio',
            'matType' => 'sometimes|string|in:Fiction,Non-fiction',
            'contentDesc' => 'sometimes|string|in:juvenile,adult',
            'title' => 'sometimes|string|max:500',
            'author' => 'sometimes|string|max:255',
            'copyNo' => 'sometimes|string|max:255',
            'accessionNo' => 'sometimes|string|max:100',
            'areaOfResponsibility' => 'nullable|string|max:255',
            'editionStatement' => 'nullable|string|max:100',
            'publishersName' => 'nullable|string|max:255',
            'placeOfPublication' => 'sometimes|string|max:255',
            'yearOfPublication' => 'sometimes|string|max:4',
            'preliminaryPages' => 'nullable|string|max:50',
            'numberOfPages' => 'sometimes|string|max:50',
            'heightOfBook' => 'sometimes|string|max:50',
            'poBox' => 'nullable|string|max:100',
            'poBoxLocation' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'illustrations' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'nonFictionType' => 'nullable|string|in:textbook,other',
            'gnb' => 'nullable|string|max:255',
            'classification_id' => 'nullable|exists:classifications,id',
            'is_gnb_stock' => 'sometimes|boolean',
        ];

        // Conditional rules for donation
        if ($this->input('vendor') === 'donation') {
            $rules['materialSource'] = 'sometimes|string|in:Local,Foreign';
        }

        // Conditional rules for purchase
        if ($this->input('vendor') === 'purchase') {
            $rules['price'] = 'sometimes|string|max:255';
        }

        // Conditional rules for Non-fiction
        if ($this->input('matType') === 'Non-fiction') {
            $rules['nonFictionType'] = 'sometimes|string|in:textbook,other';
        }

        // ISBN validation with uniqueness check
        if ($this->has('isbn') && $this->input('isbn')) {
            $rules['isbn'] = $stockId 
                ? 'nullable|string|max:255|unique:stocks,isbn,' . $stockId
                : 'nullable|string|max:255|unique:stocks,isbn';
        }

        return $rules;
    }
}