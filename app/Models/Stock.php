<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'date',
        'vendor',
        'matForm',
        'matType',
        'contentDesc',
        'title',
        'author',
        'isbn',
        'gnb',
        'copyNo',
        'accessionNo',
        'areaOfResponsibility',
        'editionStatement',
        'publishersName',
        'placeOfPublication',
        'yearOfPublication',
        'preliminaryPages',
        'numberOfPages',
        'heightOfBook',
        'poBox',
        'poBoxLocation',
        'telephone',
        'email',
        'website',
        'illustrations',
        'subject',
        'is_gnb_stock',
        'nonFictionType',
        'classification_id',
        'sysOfClass',
        'class_number'
    ];

    protected $casts = [
        'is_gnb_stock' => 'boolean',
        'date' => 'date',
    ];

    // Append computed attributes to JSON
    protected $appends = ['contDesc'];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // Accessor for contDesc (alias for contentDesc)
    public function getContDescAttribute()
    {
        return $this->contentDesc;
    }

    // Automatically sync class_number when classification changes
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($stock) {
            if ($stock->classification_id && $stock->isDirty('classification_id')) {
                $classification = Classification::find($stock->classification_id);
                if ($classification) {
                    $stock->class_number = $classification->class_number;
                }
            }
        });
    }
}