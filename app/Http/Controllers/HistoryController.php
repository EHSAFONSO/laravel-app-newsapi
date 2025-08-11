<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Inertia\Inertia;

class HistoryController extends Controller
{
    public function index()
    {
        $searchHistory = SearchHistory::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return Inertia::render('History', [
            'searchHistory' => $searchHistory
        ]);
    }

    public function history()
    {
        $searches = SearchHistory::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return Inertia::render('SearchHistory', [
            'searches' => $searches
        ]);
    }
}