<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'contentDesc' => 'required|string|max:255',
            'writersDetails' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'issn' => 'required|string|max:255',
            'articleOrNot' => 'required|string|max:255',
            'matType' => 'required|string|max:255',
            'newspaperJournalMagazineName' => 'required|string|max:255',
            'numberOfPages' => 'required|string|max:255',
            'poBox' => 'required|string|max:255',
            'poBoxLocation' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'required|url|max:255',

        ];
    }
}
