<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class FindEmptyContent extends Command
{
    protected $signature = 'news:find-empty-content';
    protected $description = 'Encontra notícias sem conteúdo ou com conteúdo muito pequeno';

    public function handle()
    {
        $this->info('Procurando notícias sem conteúdo...');
        
        // Notícias sem conteúdo
        $emptyContent = News::whereNull('content')
            ->orWhere('content', '')
            ->orWhere('content', 'null')
            ->get();
            
        $this->info("📰 Notícias sem conteúdo ({$emptyContent->count()}):");
        foreach ($emptyContent as $news) {
            $this->line("   ID: {$news->id} | {$news->title}");
        }
        
        // Notícias com conteúdo muito pequeno (menos de 200 caracteres)
        $smallContent = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->whereRaw('LEN(content) < 200')
            ->get();
            
        $this->info("\n📰 Notícias com conteúdo pequeno ({$smallContent->count()}):");
        foreach ($smallContent as $news) {
            $this->line("   ID: {$news->id} | {$news->title} | " . strlen($news->content) . " chars");
        }
        
        $total = $emptyContent->count() + $smallContent->count();
        $this->info("\n📊 Total de notícias que precisam de atualização: {$total}");
        
        if ($total > 0) {
            $this->info("\n💡 Execute: php artisan news:update-all-content --limit={$total}");
        }
    }
}
