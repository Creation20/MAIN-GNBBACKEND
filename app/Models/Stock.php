<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\GNBService;

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
        'gnb_number',
        'gnb_year',
        'gnb_sequence',
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
        'class_number',
        'materialSource', 
        'price',          
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

    /**
     * Boot method to handle automatic GNB generation and classification category
     */
    protected static function boot()
    {
        parent::boot();

        // Handle GNB generation on creation
        static::creating(function ($stock) {
            if ($stock->date && !$stock->gnb_number) {
                $gnbService = new GNBService();
                $gnbData = $gnbService->generateGNBNumber($stock->date, 'stocks');
                
                $stock->gnb_number = $gnbData['gnb_number'];
                $stock->gnb_year = $gnbData['gnb_year'];
                $stock->gnb_sequence = $gnbData['gnb_sequence'];
            }

            // Auto-assign classification category based on class_number
            if ($stock->class_number && !$stock->sysOfClass) {
                $gnbService = new GNBService();
                $stock->sysOfClass = $gnbService->getClassificationCategory($stock->class_number);
            }
        });

        // Handle updates
        static::updating(function ($stock) {
            // Regenerate GNB if date changes
            if ($stock->isDirty('date') && $stock->date) {
                $gnbService = new GNBService();
                $gnbData = $gnbService->generateGNBNumber($stock->date, 'stocks');
                
                $stock->gnb_number = $gnbData['gnb_number'];
                $stock->gnb_year = $gnbData['gnb_year'];
                $stock->gnb_sequence = $gnbData['gnb_sequence'];
            }

            // Update classification category if class_number changes
            if ($stock->isDirty('class_number') && $stock->class_number) {
                $gnbService = new GNBService();
                $stock->sysOfClass = $gnbService->getClassificationCategory($stock->class_number);
            }

            // Sync class_number from classification if classification_id changes
            if ($stock->classification_id && $stock->isDirty('classification_id')) {
                $classification = Classification::find($stock->classification_id);
                if ($classification) {
                    $stock->class_number = $classification->class_number;
                    
                    // Also update sysOfClass
                    $gnbService = new GNBService();
                    $stock->sysOfClass = $gnbService->getClassificationCategory($classification->class_number);
                }
            }
        });
    }
}