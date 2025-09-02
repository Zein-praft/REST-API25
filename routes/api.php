<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\ProductController;
// use Illuminate\Http\Response;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::apiResource('products', ProductController::class);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;

Route::apiResource('products', ProductController::class);