<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UrlController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/generate', [URLController::class, 'store']);
Route::get('/retrieve/{shortUrl}', [URLController::class, 'retrieve']);
