<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['date','vendor','matForm','matType','contentDesc','title','author','publisher','isbn','copyNo','accessionNo','areaOfResponsibility','editionStatement','publishersName','placeOfPublication','preliminaryPages','numberOfPages','heightOfBook','poBox','poBoxLocation','telephone','email','website','illustrations','subject','is_gnb_stock','nonFictionType'];

    protected $casts = [
        'is_gnb_stock' => 'boolean',
    ];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
