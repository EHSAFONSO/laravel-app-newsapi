<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HistoryController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', ['message' => 'Inertia está funcionando!']);
});

Route::get('/test', function () {
    return Inertia::render('Test');
});

Route::get('/test-simple', function () {
    return Inertia::render('TestSimple');
});

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::post('/news/search', [NewsController::class, 'search'])->name('news.search');
Route::get('/news/search', [NewsController::class, 'search'])->name('news.search.get');
Route::get('/news/category/{category}', [NewsController::class, 'category'])->name('news.category');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');