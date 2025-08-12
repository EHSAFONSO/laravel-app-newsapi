<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HistoryController;
use Inertia\Inertia;
use App\Models\News; // Added this import for the new code

Route::get('/', function () {
    // Buscar dados do banco de dados para a página inicial
    $newsController = app(\App\Http\Controllers\NewsController::class);
    
    // Buscar notícias de tecnologia, economia e saúde do banco de dados
    $techNews = News::where('category', 'technology')
        ->orderBy('published_at', 'desc')
        ->limit(1)
        ->get()
        ->map(function ($news) {
            return [
                'id' => $news->id,
                'title' => $news->title,
                'description' => $news->description,
                'url' => $news->url,
                'urlToImage' => $news->url_to_image,
                'publishedAt' => $news->published_at,
                'source' => ['name' => $news->source_name],
                'author' => $news->author,
                'category' => $news->category
            ];
        });
    
    $businessNews = News::where('category', 'business')
        ->orderBy('published_at', 'desc')
        ->limit(1)
        ->get()
        ->map(function ($news) {
            return [
                'id' => $news->id,
                'title' => $news->title,
                'description' => $news->description,
                'url' => $news->url,
                'urlToImage' => $news->url_to_image,
                'publishedAt' => $news->published_at,
                'source' => ['name' => $news->source_name],
                'author' => $news->author,
                'category' => $news->category
            ];
        });
    
    $healthNews = News::where('category', 'health')
        ->orderBy('published_at', 'desc')
        ->limit(1)
        ->get()
        ->map(function ($news) {
            return [
                'id' => $news->id,
                'title' => $news->title,
                'description' => $news->description,
                'url' => $news->url,
                'urlToImage' => $news->url_to_image,
                'publishedAt' => $news->published_at,
                'source' => ['name' => $news->source_name],
                'author' => $news->author,
                'category' => $news->category
            ];
        });
    
    // Buscar headlines gerais do banco de dados
    $headlines = News::orderBy('published_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function ($news) {
            return [
                'id' => $news->id,
                'title' => $news->title,
                'description' => $news->description,
                'url' => $news->url,
                'urlToImage' => $news->url_to_image,
                'publishedAt' => $news->published_at,
                'source' => ['name' => $news->source_name],
                'author' => $news->author,
                'category' => $news->category
            ];
        });
    
    return Inertia::render('Welcome', [
        'techNews' => [
            'success' => $techNews->count() > 0,
            'articles' => $techNews->toArray()
        ],
        'businessNews' => [
            'success' => $businessNews->count() > 0,
            'articles' => $businessNews->toArray()
        ],
        'healthNews' => [
            'success' => $healthNews->count() > 0,
            'articles' => $healthNews->toArray()
        ],
        'headlines' => [
            'success' => $headlines->count() > 0,
            'articles' => $headlines->toArray()
        ]
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