<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['title', 'author', 'isbn', 'classification_id', 'is_gnb_stock'];

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
