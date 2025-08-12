<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Services\NewsApiService;

class UpdateSmallContent extends Command
{
    protected $signature = 'news:update-small-content {--limit=10} {--min=1000}';
    protected $description = 'Update content for news articles that have small content';

    public function handle()
    {
        $limit = $this->option('limit');
        $minChars = $this->option('min');
        
        $this->info("Atualizando notícias com conteúdo menor que {$minChars} caracteres (limite: {$limit})...");
        
        // Buscar notícias com conteúdo pequeno
        $newsWithSmallContent = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->whereRaw('LEN(content) < ?', [$minChars])
            ->limit($limit)
            ->get();
        
        $this->info("Encontradas {$newsWithSmallContent->count()} notícias com conteúdo pequeno");
        
        if ($newsWithSmallContent->count() === 0) {
            $this->info("Todas as notícias já têm conteúdo adequado!");
            return;
        }
        
        $newsApiService = app(NewsApiService::class);
        $updated = 0;
        $failed = 0;
        
        $progressBar = $this->output->createProgressBar($newsWithSmallContent->count());
        $progressBar->start();
        
        foreach ($newsWithSmallContent as $news) {
            try {
                $result = $newsApiService->getArticleDetails($news->title, $news->url);
                
                if ($result['success'] && !empty($result['article']['content']) && strlen($result['article']['content']) > $minChars) {
                    $news->update(['content' => $result['article']['content']]);
                    $updated++;
                } else {
                    $failed++;
                }
                
                // Pequena pausa para não sobrecarregar a API
                usleep(500000); // 0.5 segundos
                
            } catch (\Exception $e) {
                $failed++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("✅ Atualizadas: {$updated} notícias");
        $this->info("❌ Falharam: {$failed} notícias");
        
        // Verificar resultado final
        $totalWithGoodContent = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->whereRaw('LEN(content) >= ?', [$minChars])
            ->count();
        
        $totalNews = News::count();
        
        $this->info("📊 Estatísticas finais:");
        $this->info("   Total de notícias: {$totalNews}");
        $this->info("   Com conteúdo >= {$minChars} chars: {$totalWithGoodContent}");
        $this->info("   Com conteúdo < {$minChars} chars: " . ($totalNews - $totalWithGoodContent));
        $this->info("   Percentual com conteúdo adequado: " . round(($totalWithGoodContent / $totalNews) * 100, 1) . "%");
    }
}
