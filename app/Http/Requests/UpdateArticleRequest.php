<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'date' => 'sometimes|date',
            'articleOrNot' => 'sometimes|string|in:article,publication',
            'newspaperJournalMagazineName' => 'sometimes|string|max:255',
            'contentDesc' => 'sometimes|string|in:juvenile,adult',
            'matType' => 'sometimes|string|in:Newspaper,Journal,Magazine',
            'writersDetails' => 'sometimes|string|max:255',
            'title' => 'sometimes|string|max:500',
            'numberOfPages' => 'sometimes|string|max:255',
            'issn' => 'sometimes|string|max:255',
            'poBox' => 'sometimes|string|max:255',
            'poBoxLocation' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'website' => 'sometimes|url|max:255',
            'subject' => 'nullable|string|max:255',
            'classification_id' => 'nullable|exists:classifications,id',
        ];

        // Conditional rules for publication
        if ($this->input('articleOrNot') === 'publication') {
            $rules['vendor'] = 'sometimes|string|in:legal-deposit,donation,purchase';
            $rules['copyNo'] = 'sometimes|string|max:255';
            $rules['matForm'] = 'sometimes|string|in:Hardcopy,Softcopy,Audio,Hardcopy & Audio,Softcopy & Audio,Hardcopy/Softcopy/Audio';
            $rules['placeOfPublication'] = 'sometimes|string|max:255';
            $rules['yearOfPublication'] = 'sometimes|string|max:255';
            
            if ($this->input('vendor') === 'purchase') {
                $rules['price'] = 'sometimes|string|max:255';
            }
        }

        return $rules;
    }
}