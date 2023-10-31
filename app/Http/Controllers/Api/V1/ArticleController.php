<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function Articles(): \Illuminate\Http\JsonResponse
    {
        $articles = Article::query()->where('status',1)->get();
        return $this->ResponseRequest('success','',[$articles]);
    }

    public function product(Article $article): \Illuminate\Http\JsonResponse
    {
        return $this->ResponseRequest('success','',[$article]);
    }
    public function create(ArticleCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data_validated = $request->validated();
        $article = Article::query()->create($data_validated);
        return $this->ResponseRequest('success','',[$article]);
    }

    public function update(ArticleUpdateRequest $request,Article $article): \Illuminate\Http\JsonResponse
    {
        $data_validated = $request->validated();
        $ar = $article->query()->update($data_validated);
        return $this->ResponseRequest('success','',[$ar]);
    }

    public function delete(Article $article): \Illuminate\Http\JsonResponse
    {
        $article->update([
            'status' => 2
        ]);
        return $this->ResponseRequest('success','',['message' => 'article deleted']);
    }
}
