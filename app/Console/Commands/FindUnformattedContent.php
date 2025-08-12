<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class FindUnformattedContent extends Command
{
    protected $signature = 'news:find-unformatted {--limit=10}';
    protected $description = 'Encontra notícias sem formatação de parágrafos';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("Procurando notícias sem formatação de parágrafos...");
        
        // Buscar notícias com conteúdo mas sem quebras duplas (parágrafos)
        $unformattedNews = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->whereRaw('LEN(content) > 500') // Apenas notícias com conteúdo significativo
            ->whereRaw('CHARINDEX(char(13) + char(10) + char(13) + char(10), content) = 0') // Sem quebras duplas
            ->limit($limit)
            ->get();
            
        $this->info("📰 Notícias sem formatação de parágrafos ({$unformattedNews->count()}):");
        foreach ($unformattedNews as $news) {
            $this->line("   ID: {$news->id} | " . strlen($news->content) . " chars | {$news->title}");
        }
        
        if ($unformattedNews->count() > 0) {
            $this->info("\n💡 Execute: php artisan news:reformat-content --limit={$unformattedNews->count()}");
        } else {
            $this->info("\n✅ Todas as notícias têm formatação adequada!");
        }
    }
}
