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
        $stock = Stock::create($request->validated());
        return $this->success($stock->load('classification'), 'Stock created successfully', 201);
    }

    public function show(Stock $stock)
    {
        return $this->success($stock->load('classification'), 'Stock retrieved successfully');
    }

    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $stock->update($request->validated());
        return $this->success($stock->load('classification'), 'Stock updated successfully');
    }

    /**
     * Assign a classification to a stock
     * Can either link to existing classification or create a new one
     */
    public function assignClassification(Request $request, Stock $stock)
    {
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
    }

    /**
     * Remove classification from a stock
     */
    public function removeClassification(Stock $stock)
    {
        $stock->classification_id = null;
        $stock->save();

        return $this->success(
            $stock->load('classification'), 
            'Classification removed successfully'
        );
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return $this->success(null, 'Stock deleted successfully');
    }
}