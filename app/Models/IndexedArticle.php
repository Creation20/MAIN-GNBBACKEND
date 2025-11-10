<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndexedArticle extends Model
{
    protected $fillable = ['title', 'author', 'publication', 'is_gnb_stock'];

    protected $casts = [
        'is_gnb_stock' => 'boolean',
    ];
}
