<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestApiDirect extends Command
{
    protected $signature = 'news:test-api-direct';
    protected $description = 'Testa a API diretamente sem cache';

    public function handle()
    {
        $this->info('Testando API diretamente...');
        
        $apiKey = env('NEWS_API_KEY', '534d12e4d1754b0a898e324454e122c8');
        $baseUrl = 'https://newsapi.org/v2';
        
        try {
            $this->info('Fazendo requisição para: ' . $baseUrl . '/top-headlines');
            $this->info('API Key: ' . substr($apiKey, 0, 10) . '...');
            
            $response = Http::timeout(30)->get($baseUrl . '/top-headlines', [
                'country' => 'us',
                'apiKey' => $apiKey,
                'page' => 1,
                'pageSize' => 1
            ]);
            
            $this->info('Status da resposta: ' . $response->status());
            $this->info('Headers: ' . json_encode($response->headers()));
            
            if ($response->successful()) {
                $data = $response->json();
                $this->info('✅ API funcionando!');
                $this->info('Total de resultados: ' . ($data['totalResults'] ?? 0));
                $this->info('Número de artigos: ' . count($data['articles'] ?? []));
                
                if (!empty($data['articles'])) {
                    $article = $data['articles'][0];
                    $this->info('Primeiro artigo:');
                    $this->info('  Título: ' . ($article['title'] ?? 'N/A'));
                    $this->info('  Fonte: ' . ($article['source']['name'] ?? 'N/A'));
                    $this->info('  URL: ' . ($article['url'] ?? 'N/A'));
                }
            } else {
                $this->error('❌ API retornou erro: ' . $response->status());
                $this->error('Body: ' . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erro na requisição: ' . $e->getMessage());
        }
    }
}
