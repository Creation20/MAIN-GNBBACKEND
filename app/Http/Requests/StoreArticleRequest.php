<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'date' => 'required|date',
            'articleOrNot' => 'required|string|in:Article,Publication',
            'newspaperJournalMagazineName' => 'nullable|string|max:255',
            'contentDesc' => 'required|string|in:Juvenile,Adult',
            'matType' => 'required|string|in:Newspaper,Journal,Magazine',
            'writersDetails' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'numberOfPages' => 'nullable|string|max:255',
            'issn' => 'required|string|max:255',
            'poBox' => 'nullable|string|max:255',
            'poBoxLocation' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'subject' => 'nullable|string|max:255',
            'classification_id' => 'nullable|exists:classifications,id',
        ];

        // Conditional rules for Publication
        if ($this->input('articleOrNot') === 'Publication') {
            $rules['vendor'] = 'required|string|in:Legal Deposits,Donation,Purchase';
            $rules['copyNo'] = 'required|string|max:255';
            $rules['matForm'] = 'required|string|in:Hardcopy,Softcopy,Audio,Hardcopy & Audio,Softcopy & Audio,Hardcopy/Softcopy/Audio';
            $rules['placeOfPublication'] = 'required|string|max:255';
            $rules['yearOfPublication'] = 'required|string|max:255';
            
            // Conditional rule for Purchase
            if ($this->input('vendor') === 'Purchase') {
                $rules['price'] = 'required|string|max:255';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'articleOrNot.required' => 'Article or publication selection is required',
            'vendor.required' => 'Vendor is required when article type is Publication',
            'copyNo.required' => 'Copy number is required when article type is Publication',
            'matForm.required' => 'Material form is required when article type is Publication',
            'placeOfPublication.required' => 'Place of publication is required when article type is Publication',
            'yearOfPublication.required' => 'Year of publication is required when article type is Publication',
            'price.required' => 'Price is required when vendor is Purchase',
        ];
    }
}