<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login',[\App\Http\Controllers\Api\V1\AuthController::class,'login']);
Route::post('register',[\App\Http\Controllers\Api\V1\AuthController::class,'register']);

Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('article')->group(function (){
        Route::get('',[\App\Http\Controllers\Api\V1\ArticleController::class,'articles']);
        Route::get('{article}',[\App\Http\Controllers\Api\V1\ArticleController::class,'article']);
        Route::post('create',[\App\Http\Controllers\Api\V1\ArticleController::class,'create']);
        Route::put('update/{article}',[\App\Http\Controllers\Api\V1\ArticleController::class,'update']);
        Route::delete('delete/{article}',[\App\Http\Controllers\Api\V1\ArticleController::class,'delete']);
    });

    Route::prefix('product')->group(function (){
        Route::get('',[\App\Http\Controllers\Api\V1\ProductController::class,'products']);
        Route::get('{product}',[\App\Http\Controllers\Api\V1\ProductController::class,'product']);
        Route::post('create',[\App\Http\Controllers\Api\V1\ProductController::class,'create']);
        Route::put('update/{product}',[\App\Http\Controllers\Api\V1\ProductController::class,'update']);
        Route::delete('delete/{product}',[\App\Http\Controllers\Api\V1\ProductController::class,'delete']);
    });

    Route::prefix('cart')->group(function (){
        Route::get('',[\App\Http\Controllers\Api\V1\CartController::class,'index']);
        Route::post('create',[\App\Http\Controllers\Api\V1\CartController::class,'add']);
        Route::post('create',[\App\Http\Controllers\Api\V1\CartController::class,'remove']);
        Route::delete('delete',[\App\Http\Controllers\Api\V1\CartController::class,'delete']);
    });

    Route::prefix('order')->group(function (){
        Route::get('',[\App\Http\Controllers\Api\V1\OrderController::class,'all']);
        Route::post('create',[\App\Http\Controllers\Api\V1\OrderController::class,'create']);
        Route::put('change_status',[\App\Http\Controllers\Api\V1\OrderController::class,'change_status']);
    });

    Route::prefix('comment')->group(function (){
        Route::get('',[\App\Http\Controllers\Api\V1\CommentController::class,'comments']);
        Route::get('{product_id}',[\App\Http\Controllers\Api\V1\CommentController::class,'productComments']);
        Route::put('change_status/{productComment}',[\App\Http\Controllers\Api\V1\CommentController::class,'change_status']);
    });
});
