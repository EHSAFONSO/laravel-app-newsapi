<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsApiService;
use App\Models\News;
use Illuminate\Support\Facades\Log;

class FetchDailyNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca notícias da API uma vez por dia e salva no banco de dados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando busca diária de notícias...');
        
        try {
            $newsService = new NewsApiService();
            
            // Buscar notícias em destaque do Brasil
            $this->info('Buscando notícias em destaque...');
            $headlines = $newsService->getTopHeadlines('br', 1, 20);
            
            if ($headlines['success'] && !empty($headlines['articles'])) {
                $this->saveNewsToDatabase($headlines['articles'], 'headlines');
                $this->info('Notícias em destaque salvas com sucesso!');
            } else {
                $this->warn('Não foi possível buscar notícias em destaque.');
            }
            
            // Buscar notícias por categorias populares
            $categories = ['technology', 'business', 'sports', 'entertainment'];
            
            foreach ($categories as $category) {
                $this->info("Buscando notícias da categoria: {$category}");
                $categoryNews = $newsService->getNewsByCategory($category, 'br', 1, 10);
                
                if ($categoryNews['success'] && !empty($categoryNews['articles'])) {
                    $this->saveNewsToDatabase($categoryNews['articles'], $category);
                    $this->info("Notícias da categoria {$category} salvas com sucesso!");
                } else {
                    $this->warn("Não foi possível buscar notícias da categoria {$category}.");
                }
            }
            
            $this->info('Busca diária de notícias concluída com sucesso!');
            Log::info('Busca diária de notícias executada com sucesso');
            
        } catch (\Exception $e) {
            $this->error('Erro durante a busca diária de notícias: ' . $e->getMessage());
            Log::error('Erro na busca diária de notícias: ' . $e->getMessage());
        }
    }
    
    /**
     * Salva as notícias no banco de dados
     */
    private function saveNewsToDatabase($articles, $category)
    {
        foreach ($articles as $article) {
            // Verifica se a notícia já existe pelo título
            $existingNews = News::where('title', $article['title'])->first();
            
            if (!$existingNews) {
                News::create([
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
            }
        }
    }
}
