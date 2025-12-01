<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\GNBService;

class IndexedArticle extends Model
{
    protected $fillable = [
        'date',
        'contentDesc',
        'writersDetails',
        'title',
        'issn',
        'poBox',
        'poBoxLocation',
        'telephone',
        'email',
        'website',
        'articleOrNot',
        'matType',
        'newspaperJournalMagazineName',
        'numberOfPages',
        'subject',
        'classification_id',
        'gnb_number',
        'gnb_year',
        'gnb_sequence',
        'class_number',
        'sysOfClass',
        // Publication-specific fields
        'vendor',
        'copyNo',
        'matForm',
        'placeOfPublication',
        'yearOfPublication',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * Boot method to handle automatic GNB generation and classification
     */
    protected static function boot()
    {
        parent::boot();

        // Handle GNB generation and classification on creation
        static::creating(function ($article) {
            // Generate GNB if date is provided
            if ($article->date && !$article->gnb_number) {
                $gnbService = new GNBService();
                $gnbData = $gnbService->generateGNBNumber($article->date, 'indexed_articles');

                $article->gnb_number = $gnbData['gnb_number'];
                $article->gnb_year = $gnbData['gnb_year'];
                $article->gnb_sequence = $gnbData['gnb_sequence'];
            }
            
            // Sync class_number from classification if classification_id is set
            if ($article->classification_id && $article->isDirty('classification_id')) {
                $classification = Classification::find($article->classification_id);
                if ($classification) {
                    $article->class_number = $classification->class_number;
                }
            }
            
            // Auto-assign classification category based on class_number
            if ($article->class_number && !$article->sysOfClass) {
                $gnbService = new GNBService();
                $article->sysOfClass = $gnbService->getClassificationCategory($article->class_number);
            }
        });

        // Handle updates
        static::updating(function ($article) {
            // Regenerate GNB if date changes
            if ($article->isDirty('date') && $article->date) {
                $gnbService = new GNBService();
                $gnbData = $gnbService->generateGNBNumber($article->date, 'indexed_articles');

                $article->gnb_number = $gnbData['gnb_number'];
                $article->gnb_year = $gnbData['gnb_year'];
                $article->gnb_sequence = $gnbData['gnb_sequence'];
            }
            
            // Sync class_number from classification if classification_id changes
            if ($article->classification_id && $article->isDirty('classification_id')) {
                $classification = Classification::find($article->classification_id);
                if ($classification) {
                    $article->class_number = $classification->class_number;
                }
            }
            
            // Update classification category if class_number changes
            if ($article->isDirty('class_number') && $article->class_number) {
                $gnbService = new GNBService();
                $article->sysOfClass = $gnbService->getClassificationCategory($article->class_number);
            }
        });

        // Additional hook to ensure class_number is synced on save
        static::saving(function ($article) {
            if ($article->classification_id && $article->isDirty('classification_id')) {
                $classification = Classification::find($article->classification_id);
                if ($classification) {
                    $article->class_number = $classification->class_number;
                    
                    // Also update sysOfClass
                    $gnbService = new GNBService();
                    $article->sysOfClass = $gnbService->getClassificationCategory($classification->class_number);
                }
            }
        });
    }
}