<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class CheckTruncatedContent extends Command
{
    protected $signature = 'news:check-truncated {--limit=10}';
    protected $description = 'Verifica se há conteúdo truncado com [+chars] no banco de dados';

    public function handle()
    {
        $limit = $this->option('limit');
        $this->info("Verificando conteúdo truncado no banco de dados (limite: {$limit})...");
        
        // Buscar notícias que contêm [+chars]
        $truncatedNews = News::where('content', 'like', '%[+%chars%]%')
            ->orWhere('description', 'like', '%[+%chars%]%')
            ->limit($limit)
            ->get();
        
        $this->info("📰 Notícias com conteúdo truncado ({$truncatedNews->count()}):");
        
        foreach ($truncatedNews as $news) {
            $this->line("   ID: {$news->id} | " . strlen($news->content) . " chars | {$news->title}");
            
            // Mostrar onde está o [+chars]
            if (strpos($news->content, '[+') !== false) {
                $pos = strpos($news->content, '[+');
                $before = substr($news->content, max(0, $pos - 50), 50);
                $after = substr($news->content, $pos, 100);
                $this->line("      Conteúdo: ...{$before}{$after}");
            }
            
            if (strpos($news->description, '[+') !== false) {
                $pos = strpos($news->description, '[+');
                $before = substr($news->description, max(0, $pos - 50), 50);
                $after = substr($news->description, $pos, 100);
                $this->line("      Descrição: ...{$before}{$after}");
            }
            
            $this->line("      ---");
        }
        
        // Verificar se há notícias com conteúdo muito pequeno que podem estar truncadas
        $smallContent = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->whereRaw('LEN(content) < 300')
            ->where('content', 'not like', '%[+%chars%]%')
            ->limit($limit)
            ->get();
        
        $this->info("\n📰 Notícias com conteúdo pequeno que podem estar truncadas ({$smallContent->count()}):");
        foreach ($smallContent as $news) {
            $this->line("   ID: {$news->id} | " . strlen($news->content) . " chars | {$news->title}");
            $this->line("      Conteúdo: " . substr($news->content, 0, 100) . "...");
            $this->line("      ---");
        }
        
        $totalTruncated = $truncatedNews->count();
        $totalSmall = $smallContent->count();
        
        $this->info("\n📊 Resumo:");
        $this->info("   Notícias com [+chars]: {$totalTruncated}");
        $this->info("   Notícias com conteúdo pequeno: {$totalSmall}");
        $this->info("   Total que precisa de atenção: " . ($totalTruncated + $totalSmall));
        
        if ($totalTruncated > 0 || $totalSmall > 0) {
            $this->info("\n💡 Execute: php artisan news:update-small-content --limit=" . ($totalTruncated + $totalSmall));
        }
    }
}
