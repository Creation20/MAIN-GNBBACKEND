<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Stock;
use App\Traits\ApiResponse;

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

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return $this->success(null, 'Stock deleted successfully');
    }
}
