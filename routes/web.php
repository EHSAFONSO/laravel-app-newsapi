<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

Route::get('/', [NewsController::class, 'index'])->name('news.index');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/search-history', [NewsController::class, 'history'])->name('news.history');