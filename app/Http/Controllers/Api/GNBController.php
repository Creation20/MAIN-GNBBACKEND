<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\IndexedArticle;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GNBController extends Controller
{
    use ApiResponse;

    /**
     * Search by Cover Page - Shows all items from both stocks and articles
     */
    public function searchByCoverPage(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            // Get stocks
            $stocks = Stock::whereBetween('date', [$fromDate, $toDate])
                ->with('classification')
                ->get()
                ->map(function ($stock) {
                    return [
                        'title' => $stock->title,
                        'author' => $stock->author,
                        'matType' => $stock->matType,
                        'date' => $stock->date,
                        'gnb' => $stock->gnb,
                        'class_number' => $stock->class_number,
                        'type' => 'Stock'
                    ];
                });

            // Get articles
            $articles = IndexedArticle::whereBetween('date', [$fromDate, $toDate])
                ->with('classification')
                ->get()
                ->map(function ($article) {
                    return [
                        'title' => $article->title,
                        'author' => $article->writersDetails,
                        'matType' => $article->matType,
                        'date' => $article->date,
                        'gnb' => null, // Articles don't have GNB
                        'class_number' => $article->classification ? $article->classification->class_number : null,
                        'type' => 'Article'
                    ];
                });

            $results = $stocks->merge($articles)->sortBy('date')->values();

            return $this->success($results, 'Cover page search completed');
        } catch (\Exception $e) {
            return $this->error('Search failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Search Classified Index - Shows all details of classified items
     */
    public function searchByClassifiedIndex(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            // Get stocks with classifications
            $stocks = Stock::whereBetween('date', [$fromDate, $toDate])
                ->whereNotNull('classification_id')
                ->with('classification')
                ->get()
                ->map(function ($stock) {
                    return [
                        'title' => $stock->title,
                        'author' => $stock->author,
                        'class_number' => $stock->class_number,
                        'isbn' => $stock->isbn,
                        'subject' => $stock->subject,
                        'gnb' => $stock->gnb,
                        'publishersName' => $stock->publishersName,
                        'yearOfPublication' => $stock->yearOfPublication,
                        'placeOfPublication' => $stock->placeOfPublication,
                        'numberOfPages' => $stock->numberOfPages,
                        'copyNo' => $stock->copyNo,
                        'accessionNo' => $stock->accessionNo,
                        'matForm' => $stock->matForm,
                        'contentDesc' => $stock->contentDesc,
                        'vendor' => $stock->vendor,
                        'matType' => $stock->matType,
                        'date' => $stock->date,
                        'type' => 'Stock'
                    ];
                });

            // Get articles with classifications
            $articles = IndexedArticle::whereBetween('date', [$fromDate, $toDate])
                ->whereNotNull('classification_id')
                ->with('classification')
                ->get()
                ->map(function ($article) {
                    return [
                        'title' => $article->title,
                        'author' => $article->writersDetails,
                        'class_number' => $article->classification ? $article->classification->class_number : null,
                        'isbn' => $article->issn,
                        'subject' => $article->subject,
                        'gnb' => null,
                        'publishersName' => $article->newspaperJournalMagazineName,
                        'yearOfPublication' => $article->yearOfPublication,
                        'placeOfPublication' => $article->placeOfPublication,
                        'numberOfPages' => $article->numberOfPages,
                        'copyNo' => $article->copyNo,
                        'accessionNo' => null,
                        'matForm' => $article->matForm,
                        'contentDesc' => $article->contentDesc,
                        'vendor' => $article->vendor,
                        'matType' => $article->matType,
                        'date' => $article->date,
                        'type' => 'Article'
                    ];
                });

            $results = $stocks->merge($articles)->sortBy('class_number')->values();

            return $this->success($results, 'Classified index search completed');
        } catch (\Exception $e) {
            return $this->error('Search failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Search by Author/Title - Shows title, author, GNB, and classification
     */
    public function searchByAuthorTitle(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            // Get stocks
            $stocks = Stock::whereBetween('date', [$fromDate, $toDate])
                ->get()
                ->map(function ($stock) {
                    return [
                        'title' => $stock->title,
                        'author' => $stock->author,
                        'gnb' => $stock->gnb,
                        'class_number' => $stock->class_number,
                        'type' => 'Stock'
                    ];
                });

            // Get articles
            $articles = IndexedArticle::whereBetween('date', [$fromDate, $toDate])
                ->with('classification')
                ->get()
                ->map(function ($article) {
                    return [
                        'title' => $article->title,
                        'author' => $article->writersDetails,
                        'gnb' => null,
                        'class_number' => $article->classification ? $article->classification->class_number : null,
                        'type' => 'Article'
                    ];
                });

            $results = $stocks->merge($articles)->sortBy('title')->values();

            return $this->success($results, 'Author/Title search completed');
        } catch (\Exception $e) {
            return $this->error('Search failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Search by Subject Index - Shows subject, GNB, and classification
     */
    public function searchBySubjectIndex(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            // Get stocks
            $stocks = Stock::whereBetween('date', [$fromDate, $toDate])
                ->whereNotNull('subject')
                ->get()
                ->map(function ($stock) {
                    return [
                        'subject' => $stock->subject,
                        'gnb' => $stock->gnb,
                        'class_number' => $stock->class_number,
                        'type' => 'Stock'
                    ];
                });

            // Get articles
            $articles = IndexedArticle::whereBetween('date', [$fromDate, $toDate])
                ->whereNotNull('subject')
                ->with('classification')
                ->get()
                ->map(function ($article) {
                    return [
                        'subject' => $article->subject,
                        'gnb' => null,
                        'class_number' => $article->classification ? $article->classification->class_number : null,
                        'type' => 'Article'
                    ];
                });

            $results = $stocks->merge($articles)->sortBy('subject')->values();

            return $this->success($results, 'Subject index search completed');
        } catch (\Exception $e) {
            return $this->error('Search failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Search by Publishers List - Shows publisher details and book count
     */
    public function searchByPublishersList(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            // Get publishers from stocks
            $stockPublishers = Stock::whereBetween('date', [$fromDate, $toDate])
                ->whereNotNull('publishersName')
                ->select('publishersName', 'poBox', 'poBoxLocation', 'telephone')
                ->selectRaw('COUNT(*) as bookCount')
                ->groupBy('publishersName', 'poBox', 'poBoxLocation', 'telephone')
                ->get()
                ->map(function ($publisher) {
                    return [
                        'publishersName' => $publisher->publishersName,
                        'poBox' => $publisher->poBox,
                        'poBoxLocation' => $publisher->poBoxLocation,
                        'telephone' => $publisher->telephone,
                        'bookCount' => $publisher->bookCount,
                        'type' => 'Stock'
                    ];
                });

            // Get publishers from articles
            $articlePublishers = IndexedArticle::whereBetween('date', [$fromDate, $toDate])
                ->whereNotNull('newspaperJournalMagazineName')
                ->select('newspaperJournalMagazineName as publishersName', 'poBox', 'poBoxLocation', 'telephone')
                ->selectRaw('COUNT(*) as bookCount')
                ->groupBy('newspaperJournalMagazineName', 'poBox', 'poBoxLocation', 'telephone')
                ->get()
                ->map(function ($publisher) {
                    return [
                        'publishersName' => $publisher->publishersName,
                        'poBox' => $publisher->poBox,
                        'poBoxLocation' => $publisher->poBoxLocation,
                        'telephone' => $publisher->telephone,
                        'bookCount' => $publisher->bookCount,
                        'type' => 'Article'
                    ];
                });

            $results = $stockPublishers->merge($articlePublishers)
                ->sortBy('publishersName')
                ->values();

            return $this->success($results, 'Publishers list search completed');
        } catch (\Exception $e) {
            return $this->error('Search failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate GNB Report for a specific year and month
     */
    public function generateGNBReport(Request $request)
    {
        try {
            $year = $request->input('year');
            $month = $request->input('subjectMonth');
            $subjectYear = $request->input('subjectYear');

            // Build date range based on inputs
            if ($month && $month !== 'MM') {
                $monthNumber = $this->getMonthNumber($month);
                $startDate = "$subjectYear-$monthNumber-01";
                $endDate = date("Y-m-t", strtotime($startDate));
            } else {
                $startDate = "$year-01-01";
                $endDate = "$year-12-31";
            }

            // Get all GNB stocks
            $stocks = Stock::where('is_gnb_stock', true)
                ->whereBetween('date', [$startDate, $endDate])
                ->with('classification')
                ->orderBy('class_number')
                ->get();

            return $this->success([
                'period' => $month !== 'MM' ? "$month $subjectYear" : $year,
                'totalItems' => $stocks->count(),
                'items' => $stocks
            ], 'GNB Report generated successfully');
        } catch (\Exception $e) {
            return $this->error('Report generation failed: ' . $e->getMessage(), 500);
        }
    }

    private function getMonthNumber($monthName)
    {
        $months = [
            'January' => '01', 'February' => '02', 'March' => '03',
            'April' => '04', 'May' => '05', 'June' => '06',
            'July' => '07', 'August' => '08', 'September' => '09',
            'October' => '10', 'November' => '11', 'December' => '12'
        ];
        return $months[$monthName] ?? '01';
    }
}