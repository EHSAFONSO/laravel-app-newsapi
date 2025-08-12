<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NewsApiService
{
    private $apiKey;
    private $baseUrl = 'https://newsapi.org/v2';

    public function __construct()
    {
        $this->apiKey = env('NEWS_API_KEY', '534d12e4d1754b0a898e324454e122c8');
    }

    public function searchNews($query, $page = 1, $pageSize = 10)
    {
        $cacheKey = "news_search_{$query}_{$page}_{$pageSize}";
        
        // Cache por 5 minutos para evitar muitas requisições
        return Cache::remember($cacheKey, 300, function () use ($query, $page, $pageSize) {
            try {
                Log::info("Tentando buscar notícias para: {$query}");
                
                $response = Http::get($this->baseUrl . '/everything', [
                    'q' => $query,
                    'apiKey' => $this->apiKey,
                    'page' => $page,
                    'pageSize' => $pageSize,
                    'language' => 'pt',
                    'sortBy' => 'publishedAt'
                ]);

                Log::info("Resposta da API - Status: " . $response->status());
                Log::info("Resposta da API - Body: " . substr($response->body(), 0, 500) . "...");

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Total de resultados: " . ($data['totalResults'] ?? 0));
                    Log::info("Número de artigos: " . count($data['articles'] ?? []));
                    
                    // Se não há resultados em português, tenta em inglês
                    if (($data['totalResults'] ?? 0) === 0) {
                        Log::info("Nenhum resultado em português, tentando em inglês...");
                        
                        $responseEn = Http::get($this->baseUrl . '/everything', [
                            'q' => $query,
                            'apiKey' => $this->apiKey,
                            'page' => $page,
                            'pageSize' => $pageSize,
                            'language' => 'en',
                            'sortBy' => 'publishedAt'
                        ]);
                        
                        if ($responseEn->successful()) {
                            $dataEn = $responseEn->json();
                            Log::info("Total de resultados em inglês: " . ($dataEn['totalResults'] ?? 0));
                            
                            return [
                                'success' => true,
                                'articles' => $dataEn['articles'] ?? [],
                                'totalResults' => $dataEn['totalResults'] ?? 0,
                                'currentPage' => $page,
                                'totalPages' => ceil(($dataEn['totalResults'] ?? 0) / $pageSize)
                            ];
                        }
                    }
                    
                    return [
                        'success' => true,
                        'articles' => $data['articles'] ?? [],
                        'totalResults' => $data['totalResults'] ?? 0,
                        'currentPage' => $page,
                        'totalPages' => ceil(($data['totalResults'] ?? 0) / $pageSize)
                    ];
                }

                if ($response->status() === 429) {
                    Log::warning("API com limite excedido (429). Aguarde algumas horas para reset.");
                    return [
                        'success' => false,
                        'error' => 'Limite de requisições da API excedido. Aguarde algumas horas para reset.',
                        'articles' => [],
                        'totalResults' => 0,
                        'currentPage' => $page,
                        'totalPages' => 0
                    ];
                }
                
                Log::warning("API falhou, usando dados de exemplo. Status: " . $response->status());
                // Se a API falhou, retorna dados de exemplo
                return $this->getDemoSearchResults($query, $page, $pageSize);

            } catch (\Exception $e) {
                Log::error("Erro na API: " . $e->getMessage());
                // Se houve erro de conexão, retorna dados de exemplo
                return $this->getDemoSearchResults($query, $page, $pageSize);
            }
        });
    }

    public function getTopHeadlines($country = 'br', $page = 1, $pageSize = 10)
    {
        $cacheKey = "news_headlines_{$country}_{$page}_{$pageSize}";
        
        return Cache::remember($cacheKey, 300, function () use ($country, $page, $pageSize) {
            try {
                Log::info("Tentando buscar destaques para país: {$country}");
                
                $response = Http::get($this->baseUrl . '/top-headlines', [
                    'country' => $country,
                    'apiKey' => $this->apiKey,
                    'page' => $page,
                    'pageSize' => $pageSize
                ]);

                Log::info("Resposta da API - Status: " . $response->status());
                Log::info("Resposta da API - Body: " . substr($response->body(), 0, 500) . "...");

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Total de resultados: " . ($data['totalResults'] ?? 0));
                    Log::info("Número de artigos: " . count($data['articles'] ?? []));
                    
                    // Se não há resultados para o Brasil, tenta EUA
                    if (($data['totalResults'] ?? 0) === 0 && $country === 'br') {
                        Log::info("Nenhum resultado para Brasil, tentando EUA...");
                        
                        $responseUs = Http::get($this->baseUrl . '/top-headlines', [
                            'country' => 'us',
                            'apiKey' => $this->apiKey,
                            'page' => $page,
                            'pageSize' => $pageSize
                        ]);
                        
                        if ($responseUs->successful()) {
                            $dataUs = $responseUs->json();
                            Log::info("Total de resultados EUA: " . ($dataUs['totalResults'] ?? 0));
                            
                            return [
                                'success' => true,
                                'articles' => $dataUs['articles'] ?? [],
                                'totalResults' => $dataUs['totalResults'] ?? 0,
                                'currentPage' => $page,
                                'totalPages' => ceil(($dataUs['totalResults'] ?? 0) / $pageSize)
                            ];
                        }
                    }
                    
                    return [
                        'success' => true,
                        'articles' => $data['articles'] ?? [],
                        'totalResults' => $data['totalResults'] ?? 0,
                        'currentPage' => $page,
                        'totalPages' => ceil(($data['totalResults'] ?? 0) / $pageSize)
                    ];
                }

                if ($response->status() === 429) {
                    Log::warning("API com limite excedido (429). Aguarde algumas horas para reset.");
                    return [
                        'success' => false,
                        'error' => 'Limite de requisições da API excedido. Aguarde algumas horas para reset.',
                        'articles' => [],
                        'totalResults' => 0,
                        'currentPage' => $page,
                        'totalPages' => 0
                    ];
                }
                
                Log::warning("API falhou, usando dados de exemplo. Status: " . $response->status());
                // Se a API falhou, retorna dados de exemplo
                return $this->getDemoHeadlines($page, $pageSize);

            } catch (\Exception $e) {
                Log::error("Erro na API: " . $e->getMessage());
                // Se houve erro de conexão, retorna dados de exemplo
                return $this->getDemoHeadlines($page, $pageSize);
            }
        });
    }

    public function getNewsByCategory($category, $country = 'br', $page = 1, $pageSize = 10)
    {
        $cacheKey = "news_category_{$category}_{$country}_{$page}_{$pageSize}";
        
        return Cache::remember($cacheKey, 300, function () use ($category, $country, $page, $pageSize) {
            try {
                Log::info("Tentando buscar notícias da categoria: {$category}");
                
                $response = Http::get($this->baseUrl . '/top-headlines', [
                    'country' => $country,
                    'category' => $category,
                    'apiKey' => $this->apiKey,
                    'page' => $page,
                    'pageSize' => $pageSize
                ]);

                Log::info("Resposta da API - Status: " . $response->status());
                Log::info("Resposta da API - Body: " . substr($response->body(), 0, 500) . "...");

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Total de resultados: " . ($data['totalResults'] ?? 0));
                    Log::info("Número de artigos: " . count($data['articles'] ?? []));
                    
                    // Se não há resultados para o Brasil, tenta EUA
                    if (($data['totalResults'] ?? 0) === 0 && $country === 'br') {
                        Log::info("Nenhum resultado para Brasil, tentando EUA...");
                        
                        $responseUs = Http::get($this->baseUrl . '/top-headlines', [
                            'country' => 'us',
                            'category' => $category,
                            'apiKey' => $this->apiKey,
                            'page' => $page,
                            'pageSize' => $pageSize
                        ]);
                        
                        if ($responseUs->successful()) {
                            $dataUs = $responseUs->json();
                            Log::info("Total de resultados EUA: " . ($dataUs['totalResults'] ?? 0));
                            
                            return [
                                'success' => true,
                                'articles' => $dataUs['articles'] ?? [],
                                'totalResults' => $dataUs['totalResults'] ?? 0,
                                'currentPage' => $page,
                                'totalPages' => ceil(($dataUs['totalResults'] ?? 0) / $pageSize)
                            ];
                        }
                    }
                    
                    return [
                        'success' => true,
                        'articles' => $data['articles'] ?? [],
                        'totalResults' => $data['totalResults'] ?? 0,
                        'currentPage' => $page,
                        'totalPages' => ceil(($data['totalResults'] ?? 0) / $pageSize)
                    ];
                }

                if ($response->status() === 429) {
                    Log::warning("API com limite excedido (429). Aguarde algumas horas para reset.");
                    return [
                        'success' => false,
                        'error' => 'Limite de requisições da API excedido. Aguarde algumas horas para reset.',
                        'articles' => [],
                        'totalResults' => 0,
                        'currentPage' => $page,
                        'totalPages' => 0
                    ];
                }
                
                Log::warning("API falhou, usando dados de exemplo. Status: " . $response->status());
                // Se a API falhou, retorna dados de exemplo
                return $this->getDemoCategoryResults($category, $page, $pageSize);

            } catch (\Exception $e) {
                Log::error("Erro na API: " . $e->getMessage());
                // Se houve erro de conexão, retorna dados de exemplo
                return $this->getDemoCategoryResults($category, $page, $pageSize);
            }
        });
    }

    public function getArticleDetails($title, $url = null)
    {
        $cacheKey = "article_details_" . md5($title . ($url ?? ''));
        
        return Cache::remember($cacheKey, 600, function () use ($title, $url) {
            try {
                Log::info("Tentando buscar detalhes do artigo: {$title}");
                
                // PRIORIDADE 1: Se temos a URL, tentar buscar o conteúdo completo primeiro
                // (Isso não consome quota da NewsAPI)
                if ($url) {
                    $fullContent = $this->fetchFullArticleContent($url);
                    if ($fullContent['success']) {
                        Log::info("Conteúdo completo obtido da URL original (sem usar API)");
                        return [
                            'success' => true,
                            'article' => [
                                'title' => $title,
                                'url' => $url,
                                'content' => $fullContent['content'],
                                'fullContent' => true,
                                'source' => ['name' => 'Conteúdo Original']
                            ]
                        ];
                    }
                }
                
                // PRIORIDADE 2: Tentar buscar da API apenas se não temos conteúdo suficiente
                // Verificar se já temos dados suficientes no banco
                if ($url) {
                    Log::info("Tentando buscar da API apenas se necessário");
                    
                    $response = Http::get($this->baseUrl . '/everything', [
                        'q' => $title,
                        'apiKey' => $this->apiKey,
                        'pageSize' => 1,
                        'sortBy' => 'relevancy',
                        'language' => 'pt,en'
                    ]);

                    Log::info("Resposta da API para detalhes - Status: " . $response->status());

                    if ($response->successful()) {
                        $data = $response->json();
                        $articles = $data['articles'] ?? [];
                        
                        if (!empty($articles)) {
                            $article = $articles[0];
                            Log::info("Detalhes do artigo encontrados na API");
                            
                            // Tentar buscar conteúdo completo da URL do artigo (sem usar API)
                            $articleUrl = $article['url'] ?? $url;
                            if ($articleUrl) {
                                $fullContent = $this->fetchFullArticleContent($articleUrl);
                                if ($fullContent['success']) {
                                    $article['content'] = $fullContent['content'];
                                    $article['fullContent'] = true;
                                }
                            }
                            
                            return [
                                'success' => true,
                                'article' => $article
                            ];
                        }
                    }

                    if ($response->status() === 429) {
                        Log::warning("API com limite excedido (429) para detalhes do artigo.");
                        return [
                            'success' => false,
                            'error' => 'Limite de requisições da API excedido. Aguarde algumas horas para reset.'
                        ];
                    }
                }
                
                // PRIORIDADE 3: Se não conseguimos nada, retornar erro
                Log::warning("Não foi possível buscar detalhes do artigo da API");
                return [
                    'success' => false,
                    'error' => 'Não foi possível buscar detalhes do artigo.'
                ];

            } catch (\Exception $e) {
                Log::error("Erro ao buscar detalhes do artigo: " . $e->getMessage());
                return [
                    'success' => false,
                    'error' => 'Erro ao buscar detalhes do artigo: ' . $e->getMessage()
                ];
            }
        });
    }

    private function getDemoSearchResults($query, $page, $pageSize)
    {
        $demoArticles = [
            [
                'title' => 'Notícia de exemplo sobre ' . $query,
                'description' => 'Esta é uma notícia de exemplo para demonstrar o funcionamento do sistema quando não há API key configurada.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/3B82F6/FFFFFF?text=Notícia+Exemplo',
                'publishedAt' => now()->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ],
            [
                'title' => 'Outra notícia relacionada a ' . $query,
                'description' => 'Segunda notícia de exemplo para mostrar como o sistema funciona sem a API real.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/10B981/FFFFFF?text=Notícia+2',
                'publishedAt' => now()->subHours(2)->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ],
            [
                'title' => 'Terceira notícia sobre ' . $query,
                'description' => 'Terceira notícia de exemplo para completar a demonstração do sistema.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/F59E0B/FFFFFF?text=Notícia+3',
                'publishedAt' => now()->subHours(4)->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ]
        ];

        return [
            'success' => true,
            'articles' => $demoArticles,
            'totalResults' => 3,
            'currentPage' => $page,
            'totalPages' => 1
        ];
    }

    private function getDemoHeadlines($page, $pageSize)
    {
        $demoArticles = [
            [
                'title' => 'Notícia em Destaque - Tecnologia',
                'description' => 'Nova tecnologia revoluciona o mercado de desenvolvimento web.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/3B82F6/FFFFFF?text=Destaque+1',
                'publishedAt' => now()->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ],
            [
                'title' => 'Notícia em Destaque - Esportes',
                'description' => 'Grande vitória no campeonato nacional surpreende torcedores.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/10B981/FFFFFF?text=Destaque+2',
                'publishedAt' => now()->subHours(1)->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ],
            [
                'title' => 'Notícia em Destaque - Política',
                'description' => 'Novas medidas governamentais impactam a economia nacional.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/F59E0B/FFFFFF?text=Destaque+3',
                'publishedAt' => now()->subHours(3)->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ]
        ];

        return [
            'success' => true,
            'articles' => $demoArticles,
            'totalResults' => 3,
            'currentPage' => $page,
            'totalPages' => 1
        ];
    }

    private function getDemoCategoryResults($category, $page, $pageSize)
    {
        $categoryNames = [
            'general' => 'Geral',
            'business' => 'Negócios',
            'technology' => 'Tecnologia',
            'sports' => 'Esportes',
            'entertainment' => 'Entretenimento',
            'health' => 'Saúde',
            'science' => 'Ciência'
        ];

        $categoryName = $categoryNames[$category] ?? 'Geral';

        $demoArticles = [
            [
                'title' => 'Notícia de ' . $categoryName . ' - Exemplo 1',
                'description' => 'Esta é uma notícia de exemplo da categoria ' . $categoryName . ' para demonstrar o funcionamento do sistema.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/3B82F6/FFFFFF?text=' . urlencode($categoryName) . '+1',
                'publishedAt' => now()->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ],
            [
                'title' => 'Notícia de ' . $categoryName . ' - Exemplo 2',
                'description' => 'Segunda notícia de exemplo da categoria ' . $categoryName . ' para mostrar o sistema funcionando.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/10B981/FFFFFF?text=' . urlencode($categoryName) . '+2',
                'publishedAt' => now()->subHours(2)->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ],
            [
                'title' => 'Notícia de ' . $categoryName . ' - Exemplo 3',
                'description' => 'Terceira notícia de exemplo da categoria ' . $categoryName . ' para completar a demonstração.',
                'url' => 'https://example.com',
                'urlToImage' => 'https://via.placeholder.com/400x200/F59E0B/FFFFFF?text=' . urlencode($categoryName) . '+3',
                'publishedAt' => now()->subHours(4)->toISOString(),
                'source' => ['name' => 'Portal de Notícias']
            ]
        ];

        return [
            'success' => true,
            'articles' => $demoArticles,
            'totalResults' => 3,
            'currentPage' => $page,
            'totalPages' => 1
        ];
    }

    /**
     * Busca o conteúdo completo de um artigo a partir da URL original
     */
    private function fetchFullArticleContent($url)
    {
        try {
            Log::info("Tentando buscar conteúdo completo de: {$url}");
            
            $response = Http::timeout(30)->get($url);
            
            if ($response->successful()) {
                $html = $response->body();
                
                // Remover scripts e estilos
                $html = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $html);
                $html = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/mi', '', $html);
                
                // Tentar diferentes seletores para encontrar o conteúdo principal
                $content = $this->extractArticleContent($html);
                
                if ($content) {
                    Log::info("Conteúdo extraído com sucesso");
                    return [
                        'success' => true,
                        'content' => $content
                    ];
                }
            }
            
            Log::warning("Não foi possível extrair conteúdo de: {$url}");
            return [
                'success' => false,
                'error' => 'Não foi possível extrair o conteúdo do artigo'
            ];
            
        } catch (\Exception $e) {
            Log::error("Erro ao buscar conteúdo completo: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erro ao buscar conteúdo: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Extrai o conteúdo principal de uma página HTML
     */
    private function extractArticleContent($html)
    {
        // Criar um DOMDocument para parsing
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);
        
        // Seletores XPath válidos para conteúdo de artigos
        $selectors = [
            '//article',
            '//*[contains(@class, "article")]',
            '//*[contains(@class, "content")]',
            '//*[contains(@class, "post")]',
            '//*[contains(@class, "story")]',
            '//*[@class="entry-content"]',
            '//*[@class="post-content"]',
            '//*[@class="article-content"]',
            '//*[@class="story-content"]',
            '//*[@class="content-body"]',
            '//*[@class="post-body"]',
            '//*[@class="article-body"]',
            '//*[@class="story-body"]',
            '//main',
            '//*[@class="main-content"]',
            '//*[@class="main-article"]'
        ];
        
        foreach ($selectors as $selector) {
            try {
                $elements = $xpath->query($selector);
                
                if ($elements && $elements->length > 0) {
                    $content = '';
                    foreach ($elements as $element) {
                        $content .= $this->extractTextWithParagraphs($element);
                    }
                    
                    if (strlen($content) > 500) { // Se tem conteúdo significativo
                        return trim($content);
                    }
                }
            } catch (\Exception $e) {
                // Ignorar erros de XPath e continuar com o próximo seletor
                continue;
            }
        }
        
        // Fallback: tentar extrair de tags p
        try {
            $paragraphs = $xpath->query('//p');
            if ($paragraphs && $paragraphs->length > 0) {
                $content = '';
                foreach ($paragraphs as $p) {
                    $text = trim($p->textContent);
                    if (strlen($text) > 30) { // Parágrafos com conteúdo significativo
                        $content .= $text . "\n\n";
                    }
                }
                
                if (strlen($content) > 500) {
                    return trim($content);
                }
            }
        } catch (\Exception $e) {
            // Ignorar erros de XPath
        }
        
        return null;
    }

    /**
     * Extrai texto preservando a formatação dos parágrafos
     */
    private function extractTextWithParagraphs($element)
    {
        $content = '';
        
        // Processar cada nó filho
        foreach ($element->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                // Texto direto
                $text = trim($child->textContent);
                if (!empty($text)) {
                    $content .= $text . ' ';
                }
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $tagName = strtolower($child->tagName);
                
                switch ($tagName) {
                    case 'p':
                        // Parágrafo - adicionar quebra de linha
                        $text = trim($child->textContent);
                        if (!empty($text)) {
                            $content .= $text . "\n\n";
                        }
                        break;
                        
                    case 'br':
                        // Quebra de linha
                        $content .= "\n";
                        break;
                        
                    case 'h1':
                    case 'h2':
                    case 'h3':
                    case 'h4':
                    case 'h5':
                    case 'h6':
                        // Títulos - adicionar quebra de linha
                        $text = trim($child->textContent);
                        if (!empty($text)) {
                            $content .= $text . "\n\n";
                        }
                        break;
                        
                    case 'div':
                    case 'section':
                    case 'article':
                        // Containers - processar recursivamente
                        $content .= $this->extractTextWithParagraphs($child);
                        break;
                        
                    case 'ul':
                    case 'ol':
                        // Listas
                        foreach ($child->childNodes as $li) {
                            if ($li->nodeType === XML_ELEMENT_NODE && strtolower($li->tagName) === 'li') {
                                $text = trim($li->textContent);
                                if (!empty($text)) {
                                    $content .= "• " . $text . "\n";
                                }
                            }
                        }
                        $content .= "\n";
                        break;
                        
                    case 'blockquote':
                        // Citações
                        $text = trim($child->textContent);
                        if (!empty($text)) {
                            $content .= '"' . $text . '"\n\n';
                        }
                        break;
                        
                    default:
                        // Outros elementos - extrair texto simples
                        $text = trim($child->textContent);
                        if (!empty($text)) {
                            $content .= $text . ' ';
                        }
                        break;
                }
            }
        }
        
        return $content;
    }
}
