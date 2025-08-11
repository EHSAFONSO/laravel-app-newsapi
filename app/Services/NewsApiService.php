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
}
