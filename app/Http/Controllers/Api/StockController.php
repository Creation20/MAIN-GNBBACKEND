<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Stock;
use App\Models\Classification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $stocks = Stock::with('classification')->get();
        return $this->success($stocks, 'Stocks retrieved successfully');
    }

    public function store(StoreStockRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Handle conditional fields
            // Remove materialSource if vendor is not Donation
            if (!isset($data['vendor']) || $data['vendor'] !== 'Donation') {
                unset($data['materialSource']);
            }
            
            // Remove price if vendor is not Purchase
            if (!isset($data['vendor']) || $data['vendor'] !== 'Purchase') {
                unset($data['price']);
            }
            
            // Remove nonFictionType if matType is not Non-fiction
            if (!isset($data['matType']) || $data['matType'] !== 'Non-fiction') {
                unset($data['nonFictionType']);
            }
            
            $stock = Stock::create($data);
            return $this->success($stock->load('classification'), 'Stock created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create stock: ' . $e->getMessage(), 500);
        }
    }

    public function show(Stock $stock)
    {
        return $this->success($stock->load('classification'), 'Stock retrieved successfully');
    }

    public function update(UpdateStockRequest $request, Stock $stock)
    {
        try {
            $data = $request->validated();
            
            // Handle conditional fields for update
            if (isset($data['vendor']) && $data['vendor'] !== 'donation') {
                $data['materialSource'] = null;
            }
            
            if (isset($data['vendor']) && $data['vendor'] !== 'purchase') {
                $data['price'] = null;
            }
            
            if (isset($data['matType']) && $data['matType'] !== 'Non-fiction') {
                $data['nonFictionType'] = null;
            }
            
            $stock->update($data);
            return $this->success($stock->load('classification'), 'Stock updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update stock: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Assign a classification to a stock
     * Can either link to existing classification or create a new one
     */
    public function assignClassification(Request $request, Stock $stock)
    {
        try {
            $request->validate([
                'class_number' => 'required|string',
                'isbn' => 'nullable|string',
                'subject' => 'nullable|string',
            ]);

            // Find or create classification
            $classification = Classification::firstOrCreate(
                ['class_number' => $request->class_number],
                [
                    'isbn' => $request->isbn,
                    'subject' => $request->subject,
                ]
            );

            // Link stock to classification
            $stock->classification_id = $classification->id;
            
            // Update stock's own fields if provided
            if ($request->has('stock_subject')) {
                $stock->subject = $request->stock_subject;
            }
            if ($request->has('stock_isbn')) {
                $stock->isbn = $request->stock_isbn;
            }
            
            $stock->save();

            return $this->success(
                $stock->load('classification'), 
                'Classification assigned successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to assign classification: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove classification from a stock
     */
    public function removeClassification(Stock $stock)
    {
        try {
            $stock->classification_id = null;
            $stock->save();

            return $this->success(
                $stock->load('classification'), 
                'Classification removed successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to remove classification: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(Stock $stock)
    {
        try {
            $stock->delete();
            return $this->success(null, 'Stock deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete stock: ' . $e->getMessage(), 500);
        }
    }
}