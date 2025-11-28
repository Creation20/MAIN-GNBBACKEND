<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GNBService;
use App\Models\Stock;
use App\Models\IndexedArticle;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class GNBController extends Controller
{
    use ApiResponse;

    protected $gnbService;

    public function __construct(GNBService $gnbService)
    {
        $this->gnbService = $gnbService;
    }

    /**
     * Search by GNB number
     * GET /api/search-by-gnb/{gnb}
     */
    public function searchByGNB(string $gnbNumber)
    {
        try {
            // Validate GNB format
            if (!preg_match('/^GNB-\d{4}-\d{3}$/', $gnbNumber)) {
                return $this->error('Invalid GNB format. Expected format: GNB-YYYY-XXX', 400);
            }

            $result = $this->gnbService->searchByGNB($gnbNumber);

            if (!$result) {
                return $this->error('No record found with GNB number: ' . $gnbNumber, 404);
            }

            // Load full model with relationships
            if ($result['type'] === 'stock') {
                $stock = Stock::with('classification')->find($result['data']->id);
                return $this->success([
                    'type' => 'stock',
                    'data' => $stock
                ], 'Stock found successfully');
            } else {
                $article = IndexedArticle::with('classification')->find($result['data']->id);
                return $this->success([
                    'type' => 'article',
                    'data' => $article
                ], 'Article found successfully');
            }
        } catch (\Exception $e) {
            return $this->error('Search failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get GNB statistics for a specific year
     * GET /api/gnb-statistics/{year}
     */
    public function getStatistics(Request $request, int $year = null)
    {
        try {
            $year = $year ?? date('Y');

            $stockCount = Stock::where('gnb_year', $year)->count();
            $articleCount = IndexedArticle::where('gnb_year', $year)->count();
            
            $maxStockSequence = Stock::where('gnb_year', $year)->max('gnb_sequence') ?? 0;
            $maxArticleSequence = IndexedArticle::where('gnb_year', $year)->max('gnb_sequence') ?? 0;

            return $this->success([
                'year' => $year,
                'stocks' => [
                    'count' => $stockCount,
                    'max_sequence' => $maxStockSequence,
                    'next_gnb' => sprintf('GNB-%d-%03d', $year, $maxStockSequence + 1)
                ],
                'articles' => [
                    'count' => $articleCount,
                    'max_sequence' => $maxArticleSequence,
                    'next_gnb' => sprintf('GNB-%d-%03d', $year, $maxArticleSequence + 1)
                ],
                'total_count' => $stockCount + $articleCount
            ], 'GNB statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to get statistics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get classification category for a class number
     * POST /api/get-classification-category
     */
    public function getClassificationCategory(Request $request)
    {
        try {
            $request->validate([
                'class_number' => 'required|string'
            ]);

            $category = $this->gnbService->getClassificationCategory($request->class_number);

            return $this->success([
                'class_number' => $request->class_number,
                'category' => $category
            ], 'Classification category retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to get category: ' . $e->getMessage(), 500);
        }
    }
}