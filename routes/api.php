<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

// 商品IDを必要とする購入処理のAPIルート
Route::middleware('auth:sanctum')->post('/purchase', [SalesController::class, 'purchase'])->name('purchase');

// ユーザー情報取得のためのルート
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
