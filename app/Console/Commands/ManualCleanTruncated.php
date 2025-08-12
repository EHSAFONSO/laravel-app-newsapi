<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class ManualCleanTruncated extends Command
{
    protected $signature = 'news:manual-clean {--limit=10}';
    protected $description = 'Limpa manualmente o conteúdo truncado';

    public function handle()
    {
        $limit = $this->option('limit');
        $this->info("Limpando manualmente conteúdo truncado (limite: {$limit})...");
        
        // Buscar notícias que contêm [+chars]
        $truncatedNews = News::where('content', 'like', '%[%+%chars]%')
            ->limit($limit)
            ->get();
        
        $this->info("Encontradas {$truncatedNews->count()} notícias com conteúdo truncado");
        
        if ($truncatedNews->count() === 0) {
            $this->info("Nenhuma notícia com conteúdo truncado encontrada!");
            return;
        }
        
        $updated = 0;
        
        foreach ($truncatedNews as $news) {
            $this->info("\nProcessando ID {$news->id}: {$news->title}");
            $this->info("Conteúdo atual: " . strlen($news->content) . " chars");
            
            // Mostrar o conteúdo atual
            $this->line("Conteúdo atual: " . substr($news->content, 0, 200) . "...");
            
            // Limpar manualmente
            $cleanedContent = $this->cleanContent($news->content);
            
            if ($cleanedContent !== $news->content) {
                $news->update(['content' => $cleanedContent]);
                $updated++;
                $this->info("✅ Limpa: " . strlen($news->content) . " → " . strlen($cleanedContent) . " chars");
                $this->line("Novo conteúdo: " . substr($cleanedContent, 0, 200) . "...");
            } else {
                $this->info("❌ Nenhuma mudança necessária");
            }
        }
        
        $this->info("\n✅ Atualizadas: {$updated} notícias");
        
        // Verificar resultado final
        $remainingTruncated = News::where('content', 'like', '%[+%chars%]%')->count();
        $this->info("📊 Notícias ainda com [+chars]: {$remainingTruncated}");
    }
    
    private function cleanContent($content)
    {
        // Remover [… +chars] do final
        $content = preg_replace('/\s*…\s*\[+\d+\s*chars?\]\s*$/', '', $content);
        
        // Remover [+chars] do final
        $content = preg_replace('/\s*\[+\d+\s*chars?\]\s*$/', '', $content);
        
        // Remover [… +chars] de qualquer lugar
        $content = preg_replace('/\s*…\s*\[+\d+\s*chars?\]\s*/', ' ', $content);
        
        // Remover [+chars] de qualquer lugar
        $content = preg_replace('/\s*\[+\d+\s*chars?\]\s*/', ' ', $content);
        
        // Remover espaços extras
        $content = preg_replace('/\s+/', ' ', $content);
        $content = trim($content);
        
        return $content;
    }
}
