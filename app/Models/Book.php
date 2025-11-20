<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'publication_date', 'isbn', 'accessionNo', 'copyNo'];

    protected $casts = [
        'publication_date' => 'date',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
