<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class CleanTruncatedDescriptions extends Command
{
    protected $signature = 'news:clean-descriptions {--limit=20}';
    protected $description = 'Limpa descrições truncadas com [+chars]';

    public function handle()
    {
        $limit = $this->option('limit');
        $this->info("Limpando descrições truncadas (limite: {$limit})...");
        
        // Buscar notícias que contêm … na descrição
        $truncatedNews = News::where('description', 'like', '%…%')
            ->limit($limit)
            ->get();
        
        $this->info("Encontradas {$truncatedNews->count()} notícias com descrição truncada");
        
        if ($truncatedNews->count() === 0) {
            $this->info("Nenhuma notícia com descrição truncada encontrada!");
            return;
        }
        
        $updated = 0;
        
        foreach ($truncatedNews as $news) {
            $this->info("\nProcessando ID {$news->id}: {$news->title}");
            $this->info("Descrição atual: " . strlen($news->description) . " chars");
            $this->line("Descrição: " . substr($news->description, 0, 200) . "...");
            
            // Limpar a descrição removendo … e [+chars]
            $cleanedDescription = $this->cleanDescription($news->description);
            
            if ($cleanedDescription !== $news->description) {
                $news->update(['description' => $cleanedDescription]);
                $updated++;
                $this->info("✅ Limpa: " . strlen($news->description) . " → " . strlen($cleanedDescription) . " chars");
                $this->line("Nova descrição: " . substr($cleanedDescription, 0, 200) . "...");
            } else {
                $this->info("❌ Nenhuma mudança necessária");
            }
        }
        
        $this->info("\n✅ Atualizadas: {$updated} notícias");
        
        // Verificar resultado final
        $remainingTruncated = News::where('description', 'like', '%…%')->count();
        $this->info("📊 Notícias ainda com … na descrição: {$remainingTruncated}");
        
        if ($remainingTruncated > 0) {
            $this->info("\n💡 Execute: php artisan news:clean-descriptions --limit={$remainingTruncated}");
        }
    }
    
    private function cleanDescription($description)
    {
        // Remover … do final
        $description = preg_replace('/\s*…\s*$/', '', $description);
        
        // Remover [… +chars] do final
        $description = preg_replace('/\s*…\s*\[+\d+\s*chars?\]\s*$/', '', $description);
        
        // Remover [+chars] do final
        $description = preg_replace('/\s*\[+\d+\s*chars?\]\s*$/', '', $description);
        
        // Remover [… +chars] de qualquer lugar
        $description = preg_replace('/\s*…\s*\[+\d+\s*chars?\]\s*/', ' ', $description);
        
        // Remover [+chars] de qualquer lugar
        $description = preg_replace('/\s*\[+\d+\s*chars?\]\s*/', ' ', $description);
        
        // Limpar espaços extras
        $description = preg_replace('/\s+/', ' ', $description);
        $description = trim($description);
        
        return $description;
    }
}
