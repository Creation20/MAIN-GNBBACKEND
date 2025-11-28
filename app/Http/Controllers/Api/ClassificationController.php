<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassificationRequest;
use App\Http\Requests\UpdateClassificationRequest;
use App\Models\Classification;
use App\Services\GNBService;
use App\Traits\ApiResponse;

class ClassificationController extends Controller
{
    use ApiResponse;
    

    

    public function index()
    {
        return $this->success(Classification::all(), 'Classifications retrieved successfully');
    }

    public function store(StoreClassificationRequest $request)
    {
        $classification = Classification::create($request->validated());
        return $this->success($classification, 'Classification created successfully', 201);
    }

    public function show(Classification $classification)
    {
        return $this->success($classification, 'Classification retrieved successfully');
    }

    public function update(UpdateClassificationRequest $request, Classification $classification)
    {
        $classification->update($request->validated());
        return $this->success($classification, 'Classification updated successfully');
    }

    public function destroy(Classification $classification)
    {
        $classification->delete();
        return $this->success(null, 'Classification deleted successfully');
    }
}
