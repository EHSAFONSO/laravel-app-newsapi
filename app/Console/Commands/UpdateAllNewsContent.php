<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Services\NewsApiService;

class UpdateAllNewsContent extends Command
{
    protected $signature = 'news:update-all-content {--limit=10}';
    protected $description = 'Update content for all news articles that don\'t have full content';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("Atualizando conteúdo de notícias (limite: {$limit})...");
        
        // Buscar notícias sem conteúdo ou com conteúdo muito pequeno
        $newsWithoutContent = News::whereNull('content')
            ->orWhere('content', '')
            ->orWhere('content', 'null')
            ->orWhereRaw('LEN(content) < 500')
            ->limit($limit)
            ->get();
        
        $this->info("Encontradas {$newsWithoutContent->count()} notícias para atualizar");
        
        if ($newsWithoutContent->count() === 0) {
            $this->info("Todas as notícias já têm conteúdo completo!");
            return;
        }
        
        $newsApiService = app(NewsApiService::class);
        $updated = 0;
        $failed = 0;
        
        $progressBar = $this->output->createProgressBar($newsWithoutContent->count());
        $progressBar->start();
        
        foreach ($newsWithoutContent as $news) {
            try {
                $result = $newsApiService->getArticleDetails($news->title, $news->url);
                
                if ($result['success'] && !empty($result['article']['content']) && strlen($result['article']['content']) > 500) {
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
        $totalWithContent = News::whereNotNull('content')
            ->where('content', '!=', '')
            ->where('content', '!=', 'null')
            ->whereRaw('LEN(content) > 500')
            ->count();
        
        $totalNews = News::count();
        
        $this->info("📊 Estatísticas finais:");
        $this->info("   Total de notícias: {$totalNews}");
        $this->info("   Com conteúdo completo: {$totalWithContent}");
        $this->info("   Sem conteúdo: " . ($totalNews - $totalWithContent));
        $this->info("   Percentual com conteúdo: " . round(($totalWithContent / $totalNews) * 100, 1) . "%");
    }
}
