<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class CheckNewsContent extends Command
{
    protected $signature = 'news:check-content';
    protected $description = 'Check if news articles have content saved in the database';

    public function handle()
    {
        $this->info('Verificando conteúdo das notícias no banco de dados...');
        
        $totalNews = News::count();
        $this->info("Total de notícias no banco: {$totalNews}");
        
        $newsWithContent = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->count();
        
        $this->info("Notícias com conteúdo: {$newsWithContent}");
        $this->info("Notícias sem conteúdo: " . ($totalNews - $newsWithContent));
        
        if ($newsWithContent > 0) {
            $this->info("\nExemplos de notícias com conteúdo:");
            $this->info(str_repeat('-', 80));
            
            $sampleNews = News::whereNotNull('content')
                ->where('content', '!=', '')
                ->where('content', '!=', 'null')
                ->select('id', 'title', 'content')
                ->limit(3)
                ->get();
            
            foreach ($sampleNews as $news) {
                $this->info("ID: {$news->id}");
                $this->info("Título: " . substr($news->title, 0, 60) . "...");
                $this->info("Conteúdo: " . strlen($news->content) . " caracteres");
                $this->info("Primeiros 100 chars: " . substr($news->content, 0, 100) . "...");
                $this->info(str_repeat('-', 40));
            }
        } else {
            $this->warn("Nenhuma notícia tem conteúdo salvo no banco!");
            $this->info("Isso pode indicar que:");
            $this->info("1. O conteúdo não está sendo salvo corretamente");
            $this->info("2. A API não está retornando conteúdo");
            $this->info("3. O scraping de conteúdo não está funcionando");
        }
        
        // Verificar estrutura da tabela
        $this->info("\nVerificando estrutura da tabela news:");
        $columns = \Schema::getColumnListing('news');
        $this->info("Colunas: " . implode(', ', $columns));
        
        // Verificar se a coluna content existe
        if (in_array('content', $columns)) {
            $this->info("✅ Coluna 'content' existe na tabela");
        } else {
            $this->error("❌ Coluna 'content' não existe na tabela!");
        }
    }
}
