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
        $stockId = $this->route('stock') ? $this->route('stock')->id : null;

        $rules = [
            'date' => 'required|date',
            'vendor' => 'required|string|in:Legal Deposit,Donation,Purchase',
            'is_gnb_stock' => 'required|boolean',
            'contentDesc' => 'required|string|in:Juvenile,Adult',
            'matForm' => 'required|string|in:Hardcopy,Softcopy,Audio,Hardcopy & Softcopy,Hardcopy & Audio,Softcopy & Audio,Hardcopy/Softcopy/Audio',
            'matType' => 'required|string|in:Fiction,Non-fiction',
            'copyNo' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'title' => 'required|string|max:500',
            'areaOfResponsibility' => 'nullable|string|max:255',
            'editionStatement' => 'nullable|string|max:100',
            'accessionNo' => 'nullable|string|max:100',
            'publishersName' => 'nullable|string|max:255',
            'placeOfPublication' => 'required|string|max:255',
            'yearOfPublication' => 'required|string|max:4',
            'preliminaryPages' => 'nullable|string|max:50',
            'numberOfPages' => 'required|string|max:50',
            'illustrations' => 'nullable|string|max:255',
            'heightOfBook' => 'required|string|max:50',
            'poBox' => 'nullable|string|max:100',
            'poBoxLocation' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'subject' => 'nullable|string|max:255',
            'gnb' => 'nullable|string|max:255',
            'isBNOrissnNo '=> '',
            'classification_id' => 'nullable|exists:classifications,id',
        ];

        // Conditional rules for Donation vendor
        if ($this->input('vendor') === 'Donation') {
            $rules['materialSource'] = 'required|string|in:Local,Foreign';
        }

        // Conditional rules for Purchase vendor
        if ($this->input('vendor') === 'Purchase') {
            $rules['price'] = 'required|string|max:255';
        }

        // Conditional rules for Non-fiction material type
        if ($this->input('matType') === 'Non-fiction') {
            $rules['nonFictionType'] = 'required|string|in:Other,Textbook';
        }

        // ISBN validation with uniqueness check (only if provided)
        if ($this->has('isbn') && $this->input('isbn')) {
            $rules['isbn'] = $stockId 
                ? 'nullable|string|max:255|unique:stocks,isbn,' . $stockId
                : 'nullable|string|max:255|unique:stocks,isbn';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'vendor.required' => 'Vendor is required',
            'vendor.in' => 'Invalid vendor selection',
            'is_gnb_stock.required' => 'GNB Stock selection is required',
            'materialSource.required' => 'Material source is required when vendor is Donation',
            'price.required' => 'Price is required when vendor is Purchase',
            'nonFictionType.required' => 'Non-fiction type is required when material type is Non-fiction',
        ];
    }
}