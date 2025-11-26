<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndexedArticle extends Model
{
    protected $fillable = [
        'date',
        'contentDesc',
        'writersDetails',
        'title',
        'issn',
        'poBox',
        'poBoxLocation',
        'telephone',
        'email',
        'website',
        'articleOrNot',
        'matType',
        'newspaperJournalMagazineName',
        'numberOfPages',
        'subject',
        'classification_id',
        // Publication-specific fields
        'vendor',
        'copyNo',
        'matForm',
        'placeOfPublication',
        'yearOfPublication',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
}