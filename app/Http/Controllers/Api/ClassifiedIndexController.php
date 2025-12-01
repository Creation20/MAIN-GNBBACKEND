<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\IndexedArticle;
use App\Models\Classification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassifiedIndexController extends Controller
{
    use ApiResponse;

    /**
     * Get statistics for classified index
     */
    public function statistics(Request $request)
    {
        try {
            $year = $request->input('year');
            $month = $request->input('month');

            // Base query for stocks
            $stockQuery = Stock::whereNotNull('classification_id');
            
            // Base query for articles
            $articleQuery = IndexedArticle::whereNotNull('classification_id');

            // Apply year filter
            if ($year) {
                $stockQuery->whereYear('date', $year);
                $articleQuery->whereYear('date', $year);
            }

            // Apply month filter
            if ($month && $month !== 'MM') {
                $monthNumber = $this->getMonthNumber($month);
                if ($monthNumber) {
                    $stockQuery->whereMonth('date', $monthNumber);
                    $articleQuery->whereMonth('date', $monthNumber);
                }
            }

            // Get total records
            $totalStocks = $stockQuery->count();
            $totalArticles = $articleQuery->count();
            $totalRecords = $totalStocks + $totalArticles;

            // Get by classification
            $stocksByClass = Stock::select('classifications.class_number', 'classifications.subject', DB::raw('count(*) as count'))
                ->join('classifications', 'stocks.classification_id', '=', 'classifications.id')
                ->when($year, function ($q) use ($year) {
                    return $q->whereYear('stocks.date', $year);
                })
                ->when($month && $month !== 'MM', function ($q) use ($month) {
                    $monthNumber = $this->getMonthNumber($month);
                    return $monthNumber ? $q->whereMonth('stocks.date', $monthNumber) : $q;
                })
                ->groupBy('classifications.class_number', 'classifications.subject')
                ->get();

            $articlesByClass = IndexedArticle::select('classifications.class_number', 'classifications.subject', DB::raw('count(*) as count'))
                ->join('classifications', 'indexed_articles.classification_id', '=', 'classifications.id')
                ->when($year, function ($q) use ($year) {
                    return $q->whereYear('indexed_articles.date', $year);
                })
                ->when($month && $month !== 'MM', function ($q) use ($month) {
                    $monthNumber = $this->getMonthNumber($month);
                    return $monthNumber ? $q->whereMonth('indexed_articles.date', $monthNumber) : $q;
                })
                ->groupBy('classifications.class_number', 'classifications.subject')
                ->get();

            // Merge and aggregate by classification
            $byClassification = $stocksByClass->concat($articlesByClass)
                ->groupBy('class_number')
                ->map(function ($items) {
                    return [
                        'class_number' => $items->first()->class_number,
                        'subject' => $items->first()->subject,
                        'count' => $items->sum('count')
                    ];
                })
                ->values();

            return $this->success([
                'total_records' => $totalRecords,
                'by_classification' => $byClassification
            ], 'Statistics retrieved successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to get statistics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate classified index report
     */
    public function generate(Request $request)
    {
        try {
            $request->validate([
                'year' => 'required|string',
                'subjectMonth' => 'nullable|string',
                'subjectYear' => 'nullable|string'
            ]);

            $year = $request->input('year');
            $subjectMonth = $request->input('subjectMonth');
            $subjectYear = $request->input('subjectYear', $year);

            // Build query for stocks
            $stockQuery = Stock::with('classification')
                ->whereNotNull('classification_id')
                ->whereYear('date', $year);

            // Build query for articles
            $articleQuery = IndexedArticle::with('classification')
                ->whereNotNull('classification_id')
                ->whereYear('date', $year);

            // Apply month filter if provided
            if ($subjectMonth && $subjectMonth !== 'MM') {
                $monthNumber = $this->getMonthNumber($subjectMonth);
                if ($monthNumber) {
                    $stockQuery->whereMonth('date', $monthNumber);
                    $articleQuery->whereMonth('date', $monthNumber);
                }
            }

            $stocks = $stockQuery->get();
            $articles = $articleQuery->get();

            // Group by classification
            $classifiedData = [];
            
            // Process stocks
            foreach ($stocks as $stock) {
                $classNumber = $stock->classification->class_number;
                
                if (!isset($classifiedData[$classNumber])) {
                    $classifiedData[$classNumber] = [
                        'class_number' => $classNumber,
                        'subject' => $stock->classification->subject ?? 'N/A',
                        'items' => []
                    ];
                }

                $classifiedData[$classNumber]['items'][] = [
                    'id' => $stock->id,
                    'type' => 'stock',
                    'title' => $stock->title,
                    'author' => $stock->author,
                    'publisher' => $stock->publishersName,
                    'year' => $stock->yearOfPublication,
                    'pages' => $stock->numberOfPages,
                    'isbn' => $stock->isbn,
                    'gnb' => $stock->gnb_number,
                    'place_of_publication' => $stock->placeOfPublication
                ];
            }

            // Process articles
            foreach ($articles as $article) {
                $classNumber = $article->classification->class_number;
                
                if (!isset($classifiedData[$classNumber])) {
                    $classifiedData[$classNumber] = [
                        'class_number' => $classNumber,
                        'subject' => $article->classification->subject ?? 'N/A',
                        'items' => []
                    ];
                }

                $classifiedData[$classNumber]['items'][] = [
                    'id' => $article->id,
                    'type' => 'article',
                    'title' => $article->title,
                    'author' => $article->writersDetails,
                    'publisher' => $article->newspaperJournalMagazineName,
                    'year' => $article->yearOfPublication ?? 'N/A',
                    'pages' => $article->numberOfPages,
                    'isbn' => $article->issn,
                    'gnb' => null,
                    'place_of_publication' => $article->placeOfPublication ?? 'N/A'
                ];
            }

            // Sort by class number
            ksort($classifiedData);

            return $this->success([
                'period' => [
                    'year' => $year,
                    'month' => $subjectMonth ?? 'MM'
                ],
                'total_records' => count($stocks) + count($articles),
                'data' => array_values($classifiedData)
            ], 'Report generated successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to generate report: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Search classified index
     */
    public function search(Request $request)
    {
        try {
            $request->validate([
                'searchBy' => 'required|string|in:coverPage,classifiedIndex,authorOrTitle,subjectIndex,publishersList',
                'fromMonth' => 'required|string',
                'toMonth' => 'required|string',
                'fromYear' => 'required|string',
                'toYear' => 'required|string',
                'fromDay' => 'nullable|string',
                'toDay' => 'nullable|string',
                'keyword' => 'nullable|string'
            ]);

            $searchBy = $request->input('searchBy');
            $keyword = $request->input('keyword');
            
            // Build date range
            $fromDate = $this->buildDate(
                $request->input('fromYear'),
                $request->input('fromMonth'),
                $request->input('fromDay', '01')
            );
            
            $toDate = $this->buildDate(
                $request->input('toYear'),
                $request->input('toMonth'),
                $request->input('toDay', '31')
            );

            $results = [];

            switch ($searchBy) {
                case 'coverPage':
                    $results = $this->searchCoverPage($fromDate, $toDate);
                    break;

                case 'classifiedIndex':
                    $results = $this->searchClassifiedIndex($fromDate, $toDate);
                    break;

                case 'authorOrTitle':
                    $results = $this->searchAuthorOrTitle($fromDate, $toDate, $keyword);
                    break;

                case 'subjectIndex':
                    $results = $this->searchSubjectIndex($fromDate, $toDate, $keyword);
                    break;

                case 'publishersList':
                    $results = $this->searchPublishersList($fromDate, $toDate);
                    break;
            }

            return $this->success([
                'total' => count($results),
                'data' => $results
            ], 'Search completed successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to search: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Search cover page (all classified items)
     */
    private function searchCoverPage($fromDate, $toDate)
    {
        $stocks = Stock::with('classification')
            ->whereNotNull('classification_id')
            ->whereBetween('date', [$fromDate, $toDate])
            ->get();

        $articles = IndexedArticle::with('classification')
            ->whereNotNull('classification_id')
            ->whereBetween('date', [$fromDate, $toDate])
            ->get();

        return $this->formatSearchResults($stocks, $articles);
    }

    /**
     * Search classified index (same as cover page)
     */
    private function searchClassifiedIndex($fromDate, $toDate)
    {
        return $this->searchCoverPage($fromDate, $toDate);
    }

    /**
     * Search by author or title
     */
    private function searchAuthorOrTitle($fromDate, $toDate, $keyword)
    {
        if (!$keyword) {
            return [];
        }

        $stocks = Stock::with('classification')
            ->whereNotNull('classification_id')
            ->whereBetween('date', [$fromDate, $toDate])
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                      ->orWhere('author', 'like', "%{$keyword}%");
            })
            ->get();

        $articles = IndexedArticle::with('classification')
            ->whereNotNull('classification_id')
            ->whereBetween('date', [$fromDate, $toDate])
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                      ->orWhere('writersDetails', 'like', "%{$keyword}%");
            })
            ->get();

        return $this->formatSearchResults($stocks, $articles);
    }

    /**
     * Search by subject index
     */
    private function searchSubjectIndex($fromDate, $toDate, $keyword)
    {
        if (!$keyword) {
            return [];
        }

        $stocks = Stock::with('classification')
            ->whereNotNull('classification_id')
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('subject', 'like', "%{$keyword}%")
            ->get();

        $articles = IndexedArticle::with('classification')
            ->whereNotNull('classification_id')
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('subject', 'like', "%{$keyword}%")
            ->get();

        return $this->formatSearchResults($stocks, $articles);
    }

    /**
     * Search publishers list
     */
    private function searchPublishersList($fromDate, $toDate)
    {
        // For now, return all items grouped by publisher
        $stocks = Stock::with('classification')
            ->whereNotNull('classification_id')
            ->whereNotNull('publishersName')
            ->whereBetween('date', [$fromDate, $toDate])
            ->get();

        $articles = IndexedArticle::with('classification')
            ->whereNotNull('classification_id')
            ->whereNotNull('newspaperJournalMagazineName')
            ->whereBetween('date', [$fromDate, $toDate])
            ->get();

        return $this->formatSearchResults($stocks, $articles);
    }

    /**
     * Format search results
     */
    private function formatSearchResults($stocks, $articles)
    {
        $results = [];

        foreach ($stocks as $stock) {
            $results[] = [
                'id' => $stock->id,
                'type' => 'stock',
                'title' => $stock->title,
                'author' => $stock->author,
                'publishersName' => $stock->publishersName,
                'yearOfPublication' => $stock->yearOfPublication,
                'isbn' => $stock->isbn,
                'class_number' => $stock->classification->class_number ?? 'N/A',
                'subject' => $stock->subject,
                'contentDesc' => $stock->contentDesc,
                'date' => $stock->date
            ];
        }

        foreach ($articles as $article) {
            $results[] = [
                'id' => $article->id,
                'type' => 'article',
                'title' => $article->title,
                'author' => $article->writersDetails,
                'publishersName' => $article->newspaperJournalMagazineName,
                'yearOfPublication' => $article->yearOfPublication ?? 'N/A',
                'isbn' => $article->issn,
                'class_number' => $article->classification->class_number ?? 'N/A',
                'subject' => $article->subject,
                'contentDesc' => $article->contentDesc, 
                'date' => $article->date
            ];
        }

        return $results;
    }

    /**
     * Build date from components
     */
    private function buildDate($year, $month, $day = '01')
    {
        $monthNumber = $this->getMonthNumber($month);
        if (!$monthNumber) {
            $monthNumber = '01';
        }

        $day = ($day === 'DD' || !$day) ? '01' : $day;

        return "{$year}-{$monthNumber}-{$day}";
    }

    /**
     * Convert month name to number
     */
    private function getMonthNumber($monthName)
    {
        $months = [
            'January' => '01',
            'February' => '02',
            'March' => '03',
            'April' => '04',
            'May' => '05',
            'June' => '06',
            'July' => '07',
            'August' => '08',
            'September' => '09',
            'October' => '10',
            'November' => '11',
            'December' => '12'
        ];

        return $months[$monthName] ?? null;
    }
}