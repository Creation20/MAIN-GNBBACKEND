<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'nullable|date',
            'vendor' => 'nullable|string|max:255',
            'matForm' => 'nullable|string|max:255',
            'matType' => 'nullable|string|max:255',
            'contentDesc' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'author' => 'required|string|max:255',
            'copyNo' => 'nullable|string|max:255',
            'accessionNo' => 'nullable|string|max:255',
            'areaOfResponsibility' => 'nullable|string|max:255',
            'editionStatement' => 'nullable|string|max:255',
            'publishersName' => 'nullable|string|max:255',
            'placeOfPublication' => 'nullable|string|max:255',
            'yearOfPublication' => 'nullable|string|max:255',
            'preliminaryPages' => 'nullable|string|max:255',
            'numberOfPages' => 'nullable|string|max:255',
            'heightOfBook' => 'nullable|string|max:255',
            'poBox' => 'nullable|string|max:255',
            'poBoxLocation' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'illustrations' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'nonFictionType' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255|unique:stocks,isbn',
            'classification_id' => 'nullable|exists:classifications,id',
            'is_gnb_stock' => 'nullable|boolean',
        ];
    }
}
