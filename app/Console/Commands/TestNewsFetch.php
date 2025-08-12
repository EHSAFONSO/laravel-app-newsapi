<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsApiService;

class TestNewsFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:test-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a busca de notícias da API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testando busca de notícias...');
        
        try {
            $newsService = new NewsApiService();
            
            // Testar busca de notícias em destaque
            $this->info('Testando notícias em destaque...');
            $headlines = $newsService->getTopHeadlines('br', 1, 5);
            
            if ($headlines['success']) {
                $this->info('✓ Notícias em destaque obtidas com sucesso!');
                $this->info('Total de artigos: ' . count($headlines['articles']));
                
                foreach ($headlines['articles'] as $index => $article) {
                    $this->line(($index + 1) . '. ' . ($article['title'] ?? 'Sem título'));
                }
            } else {
                $this->error('✗ Erro ao buscar notícias em destaque: ' . ($headlines['error'] ?? 'Erro desconhecido'));
            }
            
            // Testar busca por categoria
            $this->info('Testando busca por categoria (technology)...');
            $categoryNews = $newsService->getNewsByCategory('technology', 'br', 1, 3);
            
            if ($categoryNews['success']) {
                $this->info('✓ Notícias de tecnologia obtidas com sucesso!');
                $this->info('Total de artigos: ' . count($categoryNews['articles']));
            } else {
                $this->error('✗ Erro ao buscar notícias de tecnologia: ' . ($categoryNews['error'] ?? 'Erro desconhecido'));
            }
            
            $this->info('Teste concluído!');
            
        } catch (\Exception $e) {
            $this->error('Erro durante o teste: ' . $e->getMessage());
        }
    }
}
