<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class ReformatContent extends Command
{
    protected $signature = 'news:reformat-content {--limit=10}';
    protected $description = 'Reformata notícias sem parágrafos, adicionando quebras de linha apropriadas';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("Reformatando notícias sem parágrafos (limite: {$limit})...");
        
        // Buscar notícias sem formatação
        $unformattedNews = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->whereRaw('LEN(content) > 500')
            ->whereRaw('CHARINDEX(char(13) + char(10) + char(13) + char(10), content) = 0')
            ->limit($limit)
            ->get();
        
        $this->info("Encontradas {$unformattedNews->count()} notícias para reformatar");
        
        if ($unformattedNews->count() === 0) {
            $this->info("Todas as notícias já têm formatação adequada!");
            return;
        }
        
        $updated = 0;
        $failed = 0;
        
        $progressBar = $this->output->createProgressBar($unformattedNews->count());
        $progressBar->start();
        
        foreach ($unformattedNews as $news) {
            try {
                $formattedContent = $this->formatContent($news->content);
                
                if ($formattedContent !== $news->content) {
                    $news->update(['content' => $formattedContent]);
                    $updated++;
                } else {
                    $failed++;
                }
                
            } catch (\Exception $e) {
                $failed++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("✅ Reformadas: {$updated} notícias");
        $this->info("❌ Falharam: {$failed} notícias");
    }
    
    private function formatContent($content)
    {
        // Remover espaços extras no início e fim
        $content = trim($content);
        
        // Adicionar quebras de linha após pontos finais seguidos de espaço e letra maiúscula
        $content = preg_replace('/\.\s+([A-Z])/', ".\n\n$1", $content);
        
        // Adicionar quebras de linha após pontos de exclamação
        $content = preg_replace('/!\s+([A-Z])/', "!\n\n$1", $content);
        
        // Adicionar quebras de linha após pontos de interrogação
        $content = preg_replace('/\?\s+([A-Z])/', "?\n\n$1", $content);
        
        // Adicionar quebras de linha após aspas de fechamento
        $content = preg_replace('/"\s+([A-Z])/', "\"\n\n$1", $content);
        
        // Adicionar quebras de linha após dois pontos seguidos de espaço e letra maiúscula
        $content = preg_replace('/:\s+([A-Z][a-z]{2,})/', ":\n\n$1", $content);
        
        // Limpar quebras de linha múltiplas
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        
        // Remover quebras de linha no início e fim
        $content = trim($content);
        
        return $content;
    }
}
