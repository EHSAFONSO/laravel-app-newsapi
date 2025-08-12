<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsApiService;

class TestApiLimit extends Command
{
    protected $signature = 'news:test-api-limit';
    protected $description = 'Testa se a API ainda está com limite excedido';

    public function handle()
    {
        $this->info('Testando limite da API...');
        
        try {
            $newsService = new NewsApiService();
            
            // Teste simples
            $result = $newsService->getTopHeadlines('br', 1, 1);
            
            if ($result['success']) {
                $this->info('✅ API funcionando! Limite resetado.');
                $this->info('Total de artigos: ' . count($result['articles']));
            } else {
                $this->error('❌ API ainda com limite excedido: ' . $result['error']);
            }
            
        } catch (\Exception $e) {
            $this->error('Erro: ' . $e->getMessage());
        }
    }
}
