<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\GNBService;

class Classification extends Model
{
    protected $fillable = ['class_number', 'isbn', 'subject'];

    protected $appends = ['sysOfClass'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);

    }

    public function indexedArticles()
    {
        return $this->hasMany(IndexedArticle::class);
    }

    public function getSysOfClassAttribute()
    {
        if ($this->class_number) {
            $gnbService = new GNBService();
            return $gnbService->getClassificationCategory($this->class_number);
        }
        return null;
    }
}
