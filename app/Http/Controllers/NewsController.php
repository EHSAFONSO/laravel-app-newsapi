<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use App\Models\Search;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $query = $request->input('query');

        if ($query) {
            // Salvar busca
            Search::create(['query' => $query]);

            // Busca por título usando /everything
            $response = Http::get('https://newsapi.org/v2/everything', [
                'q' => $query,
                'searchIn' => 'title',
                'apiKey' => env('NEWS_API_KEY'),
                'page' => $page,
                'pageSize' => 10,
            ]);
        } else {
            // Top headlines padrão
            $response = Http::get('https://newsapi.org/v2/top-headlines', [
                'country' => 'us',
                'apiKey' => env('NEWS_API_KEY'),
                'page' => $page,
                'pageSize' => 10,
            ]);
        }

        $data = $response->json();

        if ($data['status'] !== 'ok') {
            return Inertia::render('News/Index', ['error' => $data['message'] ?? 'Erro na API']);
        }

        return Inertia::render('News/Index', [
            'articles' => $data['articles'],
            'totalResults' => $data['totalResults'],
            'currentPage' => $page,
            'query' => $query,
        ]);
    }

    public function history()
    {
        $searches = Search::latest()->paginate(10);

        return Inertia::render('History', ['searches' => $searches]);
    }
    public function apiIndex()
{
    return News::all();
    // Retorne os dados que você precisa, por exemplo:
    //return response()->json(['message' => 'apiIndex funcionando!']);
}
}