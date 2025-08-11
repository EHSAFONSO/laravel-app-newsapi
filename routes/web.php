<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HistoryController;
use Inertia\Inertia;

Route::get('/', function () {
    // Buscar dados reais da API para a página inicial
    $newsApiService = app(\App\Services\NewsApiService::class);
    
    // Buscar notícias de tecnologia, economia e saúde para os destaques
    $techNews = $newsApiService->getNewsByCategory('technology', 'br', 1, 1);
    $businessNews = $newsApiService->getNewsByCategory('business', 'br', 1, 1);
    $healthNews = $newsApiService->getNewsByCategory('health', 'br', 1, 1);
    
    // Buscar headlines gerais (limite de 10)
    $headlines = $newsApiService->getTopHeadlines('br', 1, 10);
    
    return Inertia::render('Welcome', [
        'techNews' => $techNews,
        'businessNews' => $businessNews,
        'healthNews' => $healthNews,
        'headlines' => $headlines
    ]);
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