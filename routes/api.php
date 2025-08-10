<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Models\SearchHistory;

Route::get('/news', [NewsController::class, 'apiIndex']);
Route::get('/news/search', [NewsController::class, 'apiSearch']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/recent-searches', function () {
    $searches = SearchHistory::latest()
        ->take(5)
        ->get(['title', 'created_at']);
    
    return response()->json(['searches' => $searches]);
});
