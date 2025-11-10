<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\IndexedArticle;
use App\Traits\ApiResponse;

class ArticleController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success(IndexedArticle::all(), 'Articles retrieved successfully');
    }

    public function store(StoreArticleRequest $request)
    {
        $article = IndexedArticle::create($request->validated());
        return $this->success($article, 'Article created successfully', 201);
    }

    public function show(IndexedArticle $article)
    {
        return $this->success($article, 'Article retrieved successfully');
    }

    public function update(UpdateArticleRequest $request, IndexedArticle $article)
    {
        $article->update($request->validated());
        return $this->success($article, 'Article updated successfully');
    }

    public function destroy(IndexedArticle $article)
    {
        $article->delete();
        return $this->success(null, 'Article deleted successfully');
    }
}
