<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function products(): \Illuminate\Http\JsonResponse
    {
        $products = Product::query()->where('status',1)->get();
        return $this->ResponseRequest('success','',[$products]);
    }

    public function product(Product $product): \Illuminate\Http\JsonResponse
    {
        return $this->ResponseRequest('success','',[$product]);
    }
    public function create(ProductCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data_validated = $request->validated();
        $product = Product::query()->create($data_validated);
        return $this->ResponseRequest('success','',[$product]);
    }

    public function update(ProductUpdateRequest $request,Product $product): \Illuminate\Http\JsonResponse
    {
        $data_validated = $request->validated();
        $pr = $product->query()->update($data_validated);
        return $this->ResponseRequest('success','',[$pr]);
    }

    public function delete(Product $product): \Illuminate\Http\JsonResponse
    {
        $product->update([
            'status' => 2
        ]);
        return $this->ResponseRequest('success','',['message' => 'product deleted']);
    }
}
