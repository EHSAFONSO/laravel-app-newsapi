<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use App\Models\Search;
use App\Models\News;
use App\Models\SearchHistory;
use App\Services\NewsApiService;

class NewsController extends Controller
{
    protected $newsApiService;

    public function __construct(NewsApiService $newsApiService)
    {
        $this->newsApiService = $newsApiService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $category = $request->get('category', 'general');
        
        // Buscar notícias em destaque
        $headlines = $this->newsApiService->getTopHeadlines('br', $page, 12);
        
        // Buscar notícias por categoria
        $categoryNews = $this->newsApiService->getNewsByCategory($category, 'br', $page, 12);
        
        // Verificar se há erro de limite da API
        $apiError = null;
        if (!$headlines['success'] && isset($headlines['error'])) {
            $apiError = $headlines['error'];
        } elseif (!$categoryNews['success'] && isset($categoryNews['error'])) {
            $apiError = $categoryNews['error'];
        }
        
        return Inertia::render('News/Index', [
            'headlines' => $headlines,
            'categoryNews' => $categoryNews,
            'currentCategory' => $category,
            'currentPage' => $page,
            'apiError' => $apiError,
            'categories' => [
                'general' => 'Geral',
                'business' => 'Negócios',
                'technology' => 'Tecnologia',
                'sports' => 'Esportes',
                'entertainment' => 'Entretenimento',
                'health' => 'Saúde',
                'science' => 'Ciência'
            ]
        ]);
    }

    public function history()
    {
        $searches = SearchHistory::with('user')
            ->latest()
            ->paginate(10);

        return Inertia::render('History', ['searches' => $searches]);
    }

    public function apiIndex()
    {
        // Exemplo de retorno JSON
        return response()->json(['message' => 'API News funcionando!']);
    }

    public function search(Request $request)
    {
        // Para requisições GET, pegar o título da query string
        if ($request->isMethod('get')) {
            $title = $request->query('title');
        } else {
            // Para requisições POST, validar e pegar do input
            $request->validate([
                'title' => 'required|string|max:255'
            ]);
            $title = $request->input('title');
        }

        if (!$title) {
            return Inertia::render('News/Search', [
                'searchResults' => ['error' => 'Termo de busca é obrigatório'],
                'searchTerm' => '',
                'currentPage' => 1
            ]);
        }

        $page = $request->get('page', 1);
        
        // Buscar notícias na NewsAPI
        $searchResults = $this->newsApiService->searchNews($title, $page, 12);

        // Gravar a pesquisa no histórico
        SearchHistory::create([
            'title' => $title,
            'user_id' => auth()->id() ?? null
        ]);

        return Inertia::render('News/Search', [
            'searchResults' => $searchResults,
            'searchTerm' => $title,
            'currentPage' => $page
        ]);
    }

    public function show($id)
    {
        $news = News::findOrFail($id);
        return Inertia::render('News/Show', ['news' => $news]);
    }

    public function category(Request $request, $category)
    {
        $page = $request->get('page', 1);
        
        $categoryNews = $this->newsApiService->getNewsByCategory($category, 'br', $page, 12);
        
        return Inertia::render('News/Category', [
            'categoryNews' => $categoryNews,
            'category' => $category,
            'currentPage' => $page,
            'categories' => [
                'general' => 'Geral',
                'business' => 'Negócios',
                'technology' => 'Tecnologia',
                'sports' => 'Esportes',
                'entertainment' => 'Entretenimento',
                'health' => 'Saúde',
                'science' => 'Ciência'
            ]
        ]);
    }
}