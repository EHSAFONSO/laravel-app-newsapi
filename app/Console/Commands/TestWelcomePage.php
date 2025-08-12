<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class TestWelcomePage extends Command
{
    protected $signature = 'test:welcome-page';
    protected $description = 'Testa a página inicial e verifica se os dados estão sendo passados corretamente';

    public function handle()
    {
        $this->info('Testando página inicial...');
        
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
            
            $this->info('✅ Notícias de tecnologia processadas');
            $this->info('   Total: ' . $techNews->count());
            
            if ($techNews->count() > 0) {
                $article = $techNews->first();
                $this->info("   ID: {$article['id']}");
                $this->info("   Título: {$article['title']}");
                $this->info("   Descrição: " . substr($article['description'], 0, 100) . "...");
                $this->info("   Fonte: {$article['source']['name']}");
                $this->info("   Categoria: {$article['category']}");
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
            
            $this->info('✅ Notícias de business processadas');
            $this->info('   Total: ' . $businessNews->count());
            
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
            
            $this->info('✅ Notícias de health processadas');
            $this->info('   Total: ' . $healthNews->count());
            
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
            
            $this->info("\n📊 Resumo dos dados:");
            $this->info("   Tech News: " . ($data['techNews']['success'] ? '✅' : '❌'));
            $this->info("   Business News: " . ($data['businessNews']['success'] ? '✅' : '❌'));
            $this->info("   Health News: " . ($data['healthNews']['success'] ? '✅' : '❌'));
            
            $this->info("\n✅ Teste da página inicial concluído!");
            
        } catch (\Exception $e) {
            $this->error('❌ Erro: ' . $e->getMessage());
        }
    }
}
