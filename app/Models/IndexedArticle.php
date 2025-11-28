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
     * Boot method to handle automatic GNB generation
     */
    protected static function boot()
    {
        parent::boot();

        // Handle GNB generation on creation
        static::creating(function ($article) {
            if ($article->date && !$article->gnb_number) {
                $gnbService = new GNBService();
                $gnbData = $gnbService->generateGNBNumber($article->date, 'indexed_articles');

                $article->gnb_number = $gnbData['gnb_number'];
                $article->gnb_year = $gnbData['gnb_year'];
                $article->gnb_sequence = $gnbData['gnb_sequence'];
            }
             if ($article->classification_id && $article->isDirty('classification_id')) {
                    $classification = Classification::find($article->classification_id);
                    if ($classification) {
                        $article->class_number = $classification->class_number;
                    }
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
        });

        static::saving(function ($article) {
            if ($article->classification_id && $article->isDirty('classification_id')) {
                $classification = Classification::find($article->classification_id);
                if ($classification) {
                    $article->class_number = $classification->class_number;
                }
            }
        });
    }
}
