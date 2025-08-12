<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Services\NewsApiService;

class UpdateEmptyContent extends Command
{
    protected $signature = 'news:update-empty-content {--limit=10}';
    protected $description = 'Update content for news articles that have no content at all';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("Atualizando notícias sem conteúdo (limite: {$limit})...");
        
        // Buscar apenas notícias sem conteúdo
        $newsWithoutContent = News::whereNull('content')
            ->orWhere('content', '')
            ->orWhere('content', 'null')
            ->limit($limit)
            ->get();
        
        $this->info("Encontradas {$newsWithoutContent->count()} notícias sem conteúdo");
        
        if ($newsWithoutContent->count() === 0) {
            $this->info("Todas as notícias já têm conteúdo!");
            return;
        }
        
        $newsApiService = app(NewsApiService::class);
        $updated = 0;
        $failed = 0;
        
        $progressBar = $this->output->createProgressBar($newsWithoutContent->count());
        $progressBar->start();
        
        foreach ($newsWithoutContent as $news) {
            try {
                $this->info("\nProcessando ID {$news->id}: {$news->title}");
                
                $result = $newsApiService->getArticleDetails($news->title, $news->url);
                
                if ($result['success'] && !empty($result['article']['content']) && strlen($result['article']['content']) > 200) {
                    $news->update(['content' => $result['article']['content']]);
                    $updated++;
                    $this->info("✅ Atualizada com " . strlen($result['article']['content']) . " caracteres");
                } else {
                    $failed++;
                    $this->info("❌ Falhou: " . ($result['error'] ?? 'Conteúdo insuficiente'));
                }
                
                // Pequena pausa para não sobrecarregar a API
                usleep(1000000); // 1 segundo
                
            } catch (\Exception $e) {
                $failed++;
                $this->error("❌ Erro: " . $e->getMessage());
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
            ->count();
        
        $totalNews = News::count();
        
        $this->info("📊 Estatísticas finais:");
        $this->info("   Total de notícias: {$totalNews}");
        $this->info("   Com conteúdo: {$totalWithContent}");
        $this->info("   Sem conteúdo: " . ($totalNews - $totalWithContent));
        $this->info("   Percentual com conteúdo: " . round(($totalWithContent / $totalNews) * 100, 1) . "%");
    }
}
