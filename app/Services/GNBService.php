<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Exception;

class GNBService
{
    /**
     * Generate GNB number for a stock or article
     * 
     * @param string $date Date in Y-m-d format
     * @param string $table Table name ('stocks' or 'indexed_articles')
     * @return array ['gnb_number' => 'GNB-2025-001', 'gnb_year' => 2025, 'gnb_sequence' => 1]
     * @throws Exception
     */
    public function generateGNBNumber(string $date, string $table = 'stocks'): array
    {
        try {
            // Extract year from date
            $year = (int) date('Y', strtotime($date));
            
            if (!$year) {
                throw new Exception('Invalid date provided for GNB generation');
            }

            // Use database transaction for thread-safety
            return DB::transaction(function () use ($year, $table) {
                // Lock the table row to prevent race conditions
                $maxSequence = DB::table($table)
                    ->where('gnb_year', $year)
                    ->lockForUpdate()
                    ->max('gnb_sequence');

                // Calculate next sequence number
                $sequence = $maxSequence ? $maxSequence + 1 : 1;

                // Format: GNB-YYYY-XXX (3-digit zero-padded sequence)
                $gnbNumber = sprintf('GNB-%d-%03d', $year, $sequence);

                return [
                    'gnb_number' => $gnbNumber,
                    'gnb_year' => $year,
                    'gnb_sequence' => $sequence
                ];
            });
        } catch (Exception $e) {
            throw new Exception('Failed to generate GNB number: ' . $e->getMessage());
        }
    }

    /**
     * Search for a stock or article by GNB number
     * 
     * @param string $gnbNumber GNB number to search for
     * @return array ['type' => 'stock|article', 'data' => Model]
     * @throws Exception
     */
    public function searchByGNB(string $gnbNumber): ?array
    {
        // Search in stocks first
        $stock = DB::table('stocks')
            ->where('gnb_number', $gnbNumber)
            ->first();

        if ($stock) {
            return [
                'type' => 'stock',
                'data' => $stock
            ];
        }

        // Search in indexed_articles
        $article = DB::table('indexed_articles')
            ->where('gnb_number', $gnbNumber)
            ->first();

        if ($article) {
            return [
                'type' => 'article',
                'data' => $article
            ];
        }

        return null;
    }

    /**
     * Get classification category based on class number (000-999)
     * 
     * @param string $classNumber Classification number
     * @return string Category name
     */
    public function getClassificationCategory(string $classNumber): string
    {
        // Extract numeric part (handle formats like "823.5" or "823")
        $numeric = (int) $classNumber;

        // Determine category based on range
        if ($numeric >= 0 && $numeric <= 99) {
            return 'Generalities';
        } elseif ($numeric >= 100 && $numeric <= 199) {
            return 'Philosophy & Psychology';
        } elseif ($numeric >= 200 && $numeric <= 299) {
            return 'Religion';
        } elseif ($numeric >= 300 && $numeric <= 399) {
            return 'Social Studies';
        } elseif ($numeric >= 400 && $numeric <= 499) {
            return 'Language';
        } elseif ($numeric >= 500 && $numeric <= 599) {
            return 'Natural Sciences & Mathematics';
        } elseif ($numeric >= 600 && $numeric <= 699) {
            return 'Technology';
        } elseif ($numeric >= 700 && $numeric <= 799) {
            return 'Arts';
        } elseif ($numeric >= 800 && $numeric <= 899) {
            return 'Literature & Rhetoric';
        } elseif ($numeric >= 900 && $numeric <= 999) {
            return 'Geography & History';
        }

        return 'Uncategorized';
    }
}