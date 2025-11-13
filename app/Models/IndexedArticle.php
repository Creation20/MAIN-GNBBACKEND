<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndexedArticle extends Model
{
    protected $fillable = ['date','contentDesc','writersDetails','title','issn','poBox','poBoxLocation','telephone','email','website','articleOrNot','matType','newspaperJournalMagazineName', 'numberOfPages',];

    protected $casts = [
        'is_gnb_stock' => 'boolean',
    ];
}
