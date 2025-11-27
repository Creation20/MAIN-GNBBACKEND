<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    protected $fillable = ['title', 'class_number', 'isbn', 'subject'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);

    }

    public function indexedArticles()
    {
        return $this->hasMany(IndexedArticle::class);
    }
}
