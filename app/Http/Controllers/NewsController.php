<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use App\Models\Search;
use App\Models\News;
use App\Models\SearchHistory;
use App\Services\NewsApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class NewsController extends Controller
{
    protected $newsApiService;

    public function __construct(NewsApiService $newsApiService)
    {
        $this->newsApiService = $newsApiService;
    }

    public function index()
    {
        // PRIORIDADE: Buscar do banco de dados primeiro
        $page = request()->get('page', 1);
        $perPage = 10;
        
        // Cache para notícias principais (5 minutos)
        $cacheKey = "news_main_page_{$page}";
        $newsFromDatabase = Cache::remember($cacheKey, 300, function () use ($page, $perPage) {
            return News::orderBy('published_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
        });
        
        if ($newsFromDatabase->count() > 0) {
            Log::info("Usando notícias do banco de dados para página {$page}");
            
            $articles = $newsFromDatabase->map(function ($news) {
                return [
                    'id' => $news->id,
                    'title' => $news->title,
                    'description' => $news->description,
                    'url' => $news->url,
                    'urlToImage' => $news->url_to_image,
                    'publishedAt' => $news->published_at,
                    'source' => ['name' => $news->source_name],
                    'author' => $news->author,
                    'category' => $news->category,
                    'fromDatabase' => true
                ];
            })->toArray();
            
            return Inertia::render('News/Index', [
                'news' => [
                    'success' => true,
                    'articles' => $articles,
                    'totalResults' => $newsFromDatabase->total(),
                    'currentPage' => $page,
                    'totalPages' => $newsFromDatabase->lastPage()
                ]
            ]);
        }
        
        // Se não há dados no banco, buscar da API
        Log::info("Banco vazio, buscando da API");
        $news = $this->newsApiService->getTopHeadlines('br', $page, $perPage);
        
        if ($news['success'] && !empty($news['articles'])) {
            $news['articles'] = $this->saveAndAddIds($news['articles'], 'general');
        }
        
        return Inertia::render('News/Index', [
            'news' => $news
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
        // Rate limiting para busca (10 tentativas por minuto por IP)
        $key = 'search_' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            throw new ThrottleRequestsException("Muitas tentativas de busca. Tente novamente em {$seconds} segundos.");
        }
        RateLimiter::hit($key, 60);
        
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
        $perPage = 10;
        
        // PRIORIDADE: Buscar no banco de dados primeiro
        $searchFromDatabase = News::where('title', 'like', "%{$title}%")
            ->orWhere('description', 'like', "%{$title}%")
            ->orWhere('content', 'like', "%{$title}%")
            ->orderBy('published_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        
        if ($searchFromDatabase->count() > 0) {
            Log::info("Usando busca do banco de dados para: {$title}");
            
            $articles = $searchFromDatabase->map(function ($news) {
                return [
                    'id' => $news->id,
                    'title' => $news->title,
                    'description' => $news->description,
                    'url' => $news->url,
                    'urlToImage' => $news->url_to_image,
                    'publishedAt' => $news->published_at,
                    'source' => ['name' => $news->source_name],
                    'author' => $news->author,
                    'category' => $news->category,
                    'fromDatabase' => true
                ];
            })->toArray();
            
            $searchResults = [
                'success' => true,
                'articles' => $articles,
                'totalResults' => $searchFromDatabase->total(),
                'currentPage' => $page,
                'totalPages' => $searchFromDatabase->lastPage()
            ];
        } else {
            // Se não há resultados no banco, buscar da API
            Log::info("Nenhum resultado no banco, buscando da API para: {$title}");
            $searchResults = $this->newsApiService->searchNews($title, $page, $perPage);
            
            if ($searchResults['success'] && !empty($searchResults['articles'])) {
                $searchResults['articles'] = $this->saveAndAddIds($searchResults['articles'], 'search');
            }
        }

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
        try {
            // PRIORIDADE 1: Buscar a notícia no banco de dados
            $news = News::findOrFail($id);
            
            // PRIORIDADE 2: Verificar se já temos conteúdo suficiente no banco
            $hasGoodContent = !empty($news->content) && strlen($news->content) > 200;
            
            // Mapear categorias para nomes em português
            $categoryLabels = [
                'general' => 'Geral',
                'business' => 'Negócios',
                'technology' => 'Tecnologia',
                'sports' => 'Esportes',
                'entertainment' => 'Entretenimento',
                'health' => 'Saúde',
                'science' => 'Ciência'
            ];
            
            if ($hasGoodContent) {
                Log::info("Usando conteúdo do banco de dados para artigo ID: {$id}");
                // Usar dados do banco se já temos conteúdo bom
                $article = [
                    'id' => $news->id,
                    'title' => $news->title,
                    'description' => $news->description,
                    'content' => $news->content,
                    'url' => $news->url,
                    'urlToImage' => $news->url_to_image,
                    'publishedAt' => $news->published_at,
                    'source' => [
                        'name' => $news->source_name
                    ],
                    'author' => $news->author,
                    'category' => $news->category,
                    'categoryLabel' => $categoryLabels[$news->category] ?? ucfirst($news->category),
                    'fromDatabase' => true
                ];
                
                return Inertia::render('News/Show', [
                    'article' => $article,
                    'error' => null
                ]);
            }
            
            // PRIORIDADE 3: Só buscar da API se não temos conteúdo suficiente
            Log::info("Conteúdo insuficiente no banco, tentando API para artigo ID: {$id}");
            $articleDetails = $this->newsApiService->getArticleDetails($news->title, $news->url);
            
            if ($articleDetails['success']) {
                // Usar os dados da API com conteúdo completo
                $article = $articleDetails['article'];
                
                // Combinar dados do banco com dados da API
                $fullArticle = [
                    'id' => $news->id,
                    'title' => $article['title'] ?? $news->title,
                    'description' => $article['description'] ?? $news->description,
                    'content' => $article['content'] ?? $news->content,
                    'url' => $article['url'] ?? $news->url,
                    'urlToImage' => $article['urlToImage'] ?? $news->url_to_image,
                    'publishedAt' => $article['publishedAt'] ?? $news->published_at,
                    'source' => [
                        'name' => $article['source']['name'] ?? $news->source_name
                    ],
                    'author' => $article['author'] ?? $news->author,
                    'category' => $news->category,
                    'categoryLabel' => $categoryLabels[$news->category] ?? ucfirst($news->category),
                    'fromAPI' => true
                ];
                
                // Atualizar o banco com o novo conteúdo se conseguimos da API
                if (!empty($article['content']) && strlen($article['content']) > 200) {
                    $news->update([
                        'content' => $article['content']
                    ]);
                    Log::info("Conteúdo atualizado no banco para artigo ID: {$id}");
                }
                
                return Inertia::render('News/Show', [
                    'article' => $fullArticle,
                    'error' => null
                ]);
            } else {
                // Se não conseguir buscar da API, usar dados do banco (mesmo que limitados)
                Log::info("API falhou, usando dados do banco para artigo ID: {$id}");
                $article = [
                    'id' => $news->id,
                    'title' => $news->title,
                    'description' => $news->description,
                    'content' => $news->content,
                    'url' => $news->url,
                    'urlToImage' => $news->url_to_image,
                    'publishedAt' => $news->published_at,
                    'source' => [
                        'name' => $news->source_name
                    ],
                    'author' => $news->author,
                    'category' => $news->category,
                    'categoryLabel' => $categoryLabels[$news->category] ?? ucfirst($news->category),
                    'fromDatabase' => true
                ];
                
                return Inertia::render('News/Show', [
                    'article' => $article,
                    'error' => $articleDetails['error'] ?? 'Não foi possível buscar conteúdo completo da API'
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error("Erro ao carregar notícia ID {$id}: " . $e->getMessage());
            return Inertia::render('News/Show', [
                'article' => null,
                'error' => 'Erro ao carregar notícia: ' . $e->getMessage()
            ]);
        }
    }

    public function category(Request $request, $category)
    {
        $page = $request->get('page', 1);
        $perPage = 10;
        
        // Para categoria "general", redirecionar para a página principal
        if ($category === 'general') {
            return redirect()->route('news.index');
        }
        
        // PRIORIDADE: Buscar do banco de dados primeiro
        $categoryFromDatabase = News::where('category', $category)
            ->orderBy('published_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        
        if ($categoryFromDatabase->count() > 0) {
            Log::info("Usando notícias do banco de dados para categoria: {$category}");
            
            $articles = $categoryFromDatabase->map(function ($news) {
                return [
                    'id' => $news->id,
                    'title' => $news->title,
                    'description' => $news->description,
                    'url' => $news->url,
                    'urlToImage' => $news->url_to_image,
                    'publishedAt' => $news->published_at,
                    'source' => ['name' => $news->source_name],
                    'author' => $news->author,
                    'category' => $news->category,
                    'fromDatabase' => true
                ];
            })->toArray();
            
            $categoryNews = [
                'success' => true,
                'articles' => $articles,
                'totalResults' => $categoryFromDatabase->total(),
                'currentPage' => $page,
                'totalPages' => $categoryFromDatabase->lastPage()
            ];
        } else {
            // Se não há dados no banco, buscar da API
            Log::info("Nenhuma notícia no banco para categoria {$category}, buscando da API");
            $categoryNews = $this->newsApiService->getNewsByCategory($category, 'br', $page, $perPage);
            
            if ($categoryNews['success'] && !empty($categoryNews['articles'])) {
                $categoryNews['articles'] = $this->saveAndAddIds($categoryNews['articles'], $category);
            }
        }
        
        // Mapear categorias para nomes em português
        $categoryLabels = [
            'general' => 'Geral',
            'business' => 'Negócios',
            'technology' => 'Tecnologia',
            'sports' => 'Esportes',
            'entertainment' => 'Entretenimento',
            'health' => 'Saúde',
            'science' => 'Ciência'
        ];
        
        return Inertia::render('News/Category', [
            'categoryNews' => $categoryNews,
            'category' => $category,
            'categoryLabel' => $categoryLabels[$category] ?? ucfirst($category),
            'currentPage' => $page,
            'categories' => $categoryLabels
        ]);
    }
    
    public function saveAndAddIds($articles, $category)
    {
        $articlesWithIds = [];
        
        foreach ($articles as $article) {
            // Verificar se a notícia já existe no banco
            $existingNews = News::where('title', $article['title'])->first();
            
            if ($existingNews) {
                // Se existe, usar o ID existente
                $article['id'] = $existingNews->id;
            } else {
                // Se não existe, salvar no banco
                $news = News::create([
                    'title' => $article['title'] ?? 'Sem título',
                    'description' => $article['description'] ?? 'Sem descrição',
                    'url' => $article['url'] ?? '#',
                    'url_to_image' => $article['urlToImage'] ?? null,
                    'published_at' => $article['publishedAt'] ?? now(),
                    'source_name' => $article['source']['name'] ?? 'Fonte desconhecida',
                    'category' => $category,
                    'content' => $article['content'] ?? null,
                    'author' => $article['author'] ?? null
                ]);
                
                $article['id'] = $news->id;
            }
            
            $articlesWithIds[] = $article;
        }
        
        return $articlesWithIds;
    }
}