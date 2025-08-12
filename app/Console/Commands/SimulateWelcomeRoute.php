<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class SimulateWelcomeRoute extends Command
{
    protected $signature = 'welcome:simulate';
    protected $description = 'Simula exatamente a rota da tela inicial';

    public function handle()
    {
        $this->info('Simulando rota da tela inicial...');
        
        try {
            // Buscar notícias de tecnologia do banco de dados
            $techNews = News::where('category', 'technology')
                ->orderBy('published_at', 'desc')
                ->limit(1)
                ->get()
                ->map(function ($news) {
                    return [
                        'id' => $news->id,
                        'title' => $news->title,
                        'description' => $news->description,
                        'url' => $news->url,
                        'urlToImage' => $news->url_to_image,
                        'publishedAt' => $news->published_at,
                        'source' => ['name' => $news->source_name],
                        'author' => $news->author,
                        'category' => $news->category
                    ];
                });
            
            $this->info('✅ Tech News: ' . $techNews->count() . ' artigos');
            if ($techNews->count() > 0) {
                $article = $techNews->first();
                $this->info("   ID: {$article['id']}");
                $this->info("   Título: {$article['title']}");
                $this->info("   Descrição: " . substr($article['description'], 0, 80) . "...");
            }
            
            // Buscar notícias de business
            $businessNews = News::where('category', 'business')
                ->orderBy('published_at', 'desc')
                ->limit(1)
                ->get()
                ->map(function ($news) {
                    return [
                        'id' => $news->id,
                        'title' => $news->title,
                        'description' => $news->description,
                        'url' => $news->url,
                        'urlToImage' => $news->url_to_image,
                        'publishedAt' => $news->published_at,
                        'source' => ['name' => $news->source_name],
                        'author' => $news->author,
                        'category' => $news->category
                    ];
                });
            
            $this->info('✅ Business News: ' . $businessNews->count() . ' artigos');
            if ($businessNews->count() > 0) {
                $article = $businessNews->first();
                $this->info("   ID: {$article['id']}");
                $this->info("   Título: {$article['title']}");
                $this->info("   Descrição: " . substr($article['description'], 0, 80) . "...");
            }
            
            // Buscar notícias de health
            $healthNews = News::where('category', 'health')
                ->orderBy('published_at', 'desc')
                ->limit(1)
                ->get()
                ->map(function ($news) {
                    return [
                        'id' => $news->id,
                        'title' => $news->title,
                        'description' => $news->description,
                        'url' => $news->url,
                        'urlToImage' => $news->url_to_image,
                        'publishedAt' => $news->published_at,
                        'source' => ['name' => $news->source_name],
                        'author' => $news->author,
                        'category' => $news->category
                    ];
                });
            
            $this->info('✅ Health News: ' . $healthNews->count() . ' artigos');
            if ($healthNews->count() > 0) {
                $article = $healthNews->first();
                $this->info("   ID: {$article['id']}");
                $this->info("   Título: {$article['title']}");
                $this->info("   Descrição: " . substr($article['description'], 0, 80) . "...");
            }
            
            // Simular dados que seriam passados para o frontend
            $data = [
                'techNews' => [
                    'success' => $techNews->count() > 0,
                    'articles' => $techNews->toArray()
                ],
                'businessNews' => [
                    'success' => $businessNews->count() > 0,
                    'articles' => $businessNews->toArray()
                ],
                'healthNews' => [
                    'success' => $healthNews->count() > 0,
                    'articles' => $healthNews->toArray()
                ]
            ];
            
            $this->info("\n📊 Dados que seriam passados para o frontend:");
            $this->info("   Tech News: " . ($data['techNews']['success'] ? '✅' : '❌'));
            $this->info("   Business News: " . ($data['businessNews']['success'] ? '✅' : '❌'));
            $this->info("   Health News: " . ($data['healthNews']['success'] ? '✅' : '❌'));
            
            $this->info("\n✅ Simulação concluída! A tela inicial deve estar funcionando.");
            
        } catch (\Exception $e) {
            $this->error('❌ Erro: ' . $e->getMessage());
        }
    }
}
