<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    protected $fillable = ['class_number', 'description'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
