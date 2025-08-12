<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Services\NewsApiService;

class TestContentFetching extends Command
{
    protected $signature = 'news:test-content-fetching {id?}';
    protected $description = 'Test the content fetching process for a specific news article';

    public function handle()
    {
        $id = $this->argument('id');
        
        if (!$id) {
            // Pegar uma notícia aleatória sem conteúdo
            $news = News::whereNull('content')
                ->orWhere('content', '')
                ->orWhere('content', 'null')
                ->inRandomOrder()
                ->first();
            
            if (!$news) {
                $this->info("Todas as notícias já têm conteúdo!");
                return;
            }
            
            $id = $news->id;
        }
        
        $news = News::find($id);
        if (!$news) {
            $this->error("Notícia com ID {$id} não encontrada!");
            return;
        }
        
        $this->info("Testando busca de conteúdo para notícia ID: {$id}");
        $this->info("Título: {$news->title}");
        $this->info("URL: {$news->url}");
        $this->info("Conteúdo atual: " . (strlen($news->content ?? '') > 0 ? strlen($news->content) . ' chars' : 'VAZIO'));
        
        // Testar busca de conteúdo
        $newsApiService = app(NewsApiService::class);
        $this->info("\nBuscando conteúdo completo...");
        
        $result = $newsApiService->getArticleDetails($news->title, $news->url);
        
        if ($result['success']) {
            $this->info("✅ Conteúdo obtido com sucesso!");
            $article = $result['article'];
            
            $this->info("Título da API: " . ($article['title'] ?? 'N/A'));
            $this->info("Conteúdo da API: " . (strlen($article['content'] ?? '') > 0 ? strlen($article['content']) . ' chars' : 'VAZIO'));
            
            if (!empty($article['content']) && strlen($article['content']) > 200) {
                $this->info("✅ Conteúdo significativo obtido!");
                
                // Atualizar no banco
                $news->update(['content' => $article['content']]);
                $this->info("✅ Conteúdo salvo no banco de dados!");
                
                $this->info("\nPrimeiros 200 caracteres do conteúdo:");
                $this->info(substr($article['content'], 0, 200) . "...");
            } else {
                $this->warn("⚠️ Conteúdo obtido é muito pequeno ou vazio");
            }
        } else {
            $this->error("❌ Erro ao buscar conteúdo: " . ($result['error'] ?? 'Erro desconhecido'));
        }
    }
}
