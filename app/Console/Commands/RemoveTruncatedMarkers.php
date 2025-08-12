<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class RemoveTruncatedMarkers extends Command
{
    protected $signature = 'news:remove-truncated-markers {--limit=10}';
    protected $description = 'Remove marcadores [+chars] do conteúdo das notícias';

    public function handle()
    {
        $limit = $this->option('limit');
        $this->info("Removendo marcadores [+chars] (limite: {$limit})...");
        
        // Buscar notícias que contêm [+chars]
        $truncatedNews = News::where('content', 'like', '%[+%chars%]%')
            ->limit($limit)
            ->get();
        
        $this->info("Encontradas {$truncatedNews->count()} notícias com marcadores [+chars]");
        
        if ($truncatedNews->count() === 0) {
            $this->info("Nenhuma notícia com marcadores [+chars] encontrada!");
            return;
        }
        
        $updated = 0;
        $failed = 0;
        
        foreach ($truncatedNews as $news) {
            try {
                $this->info("\nProcessando ID {$news->id}: {$news->title}");
                $this->info("Conteúdo atual: " . strlen($news->content) . " chars");
                
                // Remover [+chars] do final
                $cleanedContent = preg_replace('/\s*\[+\d+\s*chars?\]\s*$/', '', $news->content);
                
                // Remover [+chars] de qualquer lugar no texto
                $cleanedContent = preg_replace('/\s*\[+\d+\s*chars?\]\s*/', ' ', $cleanedContent);
                
                // Limpar espaços extras
                $cleanedContent = preg_replace('/\s+/', ' ', $cleanedContent);
                $cleanedContent = trim($cleanedContent);
                
                if ($cleanedContent !== $news->content) {
                    $news->update(['content' => $cleanedContent]);
                    $updated++;
                    $this->info("✅ Limpa: " . strlen($news->content) . " → " . strlen($cleanedContent) . " chars");
                } else {
                    $failed++;
                    $this->info("❌ Nenhuma mudança necessária");
                }
                
            } catch (\Exception $e) {
                $failed++;
                $this->error("❌ Erro: " . $e->getMessage());
            }
        }
        
        $this->info("\n✅ Atualizadas: {$updated} notícias");
        $this->info("❌ Falharam: {$failed} notícias");
        
        // Verificar resultado final
        $remainingTruncated = News::where('content', 'like', '%[+%chars%]%')->count();
        $this->info("📊 Notícias ainda com [+chars]: {$remainingTruncated}");
        
        if ($remainingTruncated > 0) {
            $this->info("\n💡 Execute: php artisan news:remove-truncated-markers --limit={$remainingTruncated}");
        }
    }
}
