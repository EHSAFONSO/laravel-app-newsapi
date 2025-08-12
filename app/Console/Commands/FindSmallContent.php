<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class FindSmallContent extends Command
{
    protected $signature = 'news:find-small-content {--min=1000}';
    protected $description = 'Encontra notícias com conteúdo pequeno que podem precisar de atualização';

    public function handle()
    {
        $minChars = $this->option('min');
        
        $this->info("Procurando notícias com conteúdo menor que {$minChars} caracteres...");
        
        // Notícias com conteúdo pequeno
        $smallContent = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->whereRaw('LEN(content) < ?', [$minChars])
            ->orderByRaw('LEN(content) ASC')
            ->get();
            
        $this->info("📰 Notícias com conteúdo pequeno ({$smallContent->count()}):");
        foreach ($smallContent as $news) {
            $this->line("   ID: {$news->id} | " . strlen($news->content) . " chars | {$news->title}");
        }
        
        if ($smallContent->count() > 0) {
            $this->info("\n💡 Execute: php artisan news:update-small-content --limit={$smallContent->count()}");
        } else {
            $this->info("\n✅ Todas as notícias têm conteúdo adequado!");
        }
    }
}
