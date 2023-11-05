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
});
