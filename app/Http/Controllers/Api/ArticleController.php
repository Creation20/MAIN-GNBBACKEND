<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\IndexedArticle;
use App\Models\Classification;
use App\Traits\ApiResponse;

class ArticleController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $articles = IndexedArticle::with('classification')->get();
        return $this->success($articles, 'Articles retrieved successfully');
    }

    public function store(StoreArticleRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Only include publication fields if articleOrNot is 'Publication'
            if ($data['articleOrNot'] !== 'Publication') {
                unset($data['vendor'], $data['copyNo'], $data['matForm'], 
                      $data['placeOfPublication'], $data['yearOfPublication'], $data['price']);
            }
            
            // If classification_id is provided, get the class_number
            if (isset($data['classification_id']) && $data['classification_id']) {
                $classification = Classification::find($data['classification_id']);
                if ($classification) {
                    $data['class_number'] = $classification->class_number;
                }
            }
            
            $article = IndexedArticle::create($data);
            return $this->success($article->load('classification'), 'Article created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create article: ' . $e->getMessage(), 500);
        }
    }

    public function show(IndexedArticle $article)
    {
        return $this->success($article->load('classification'), 'Article retrieved successfully');
    }

    public function update(UpdateArticleRequest $request, IndexedArticle $article)
    {
        try {
            $data = $request->validated();
            
            // Only include publication fields if articleOrNot is 'publication'
            if (isset($data['articleOrNot']) && $data['articleOrNot'] !== 'publication') {
                unset($data['vendor'], $data['copyNo'], $data['matForm'], 
                      $data['placeOfPublication'], $data['yearOfPublication'], $data['price']);
            }
            
            // If classification_id is being updated, sync the class_number
            if (isset($data['classification_id']) && $data['classification_id']) {
                $classification = Classification::find($data['classification_id']);
                if ($classification) {
                    $data['class_number'] = $classification->class_number;
                }
            } elseif (isset($data['classification_id']) && $data['classification_id'] === null) {
                // If classification is being removed, clear the class_number
                $data['class_number'] = null;
            }
            
            $article->update($data);
            return $this->success($article->load('classification'), 'Article updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update article: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(IndexedArticle $article)
    {
        try {
            $article->delete();
            return $this->success(null, 'Article deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete article: ' . $e->getMessage(), 500);
        }
    }
}