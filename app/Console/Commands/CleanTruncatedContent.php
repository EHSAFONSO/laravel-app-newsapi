<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Services\NewsApiService;

class CleanTruncatedContent extends Command
{
    protected $signature = 'news:clean-truncated {--limit=10}';
    protected $description = 'Limpa conteúdo truncado com [+chars] e tenta obter conteúdo completo';

    public function handle()
    {
        $limit = $this->option('limit');
        $this->info("Limpando conteúdo truncado (limite: {$limit})...");
        
        // Buscar notícias que contêm [+chars]
        $truncatedNews = News::where('content', 'like', '%[+%chars%]%')
            ->limit($limit)
            ->get();
        
        $this->info("Encontradas {$truncatedNews->count()} notícias com conteúdo truncado");
        
        if ($truncatedNews->count() === 0) {
            $this->info("Nenhuma notícia com conteúdo truncado encontrada!");
            return;
        }
        
        $newsApiService = app(NewsApiService::class);
        $updated = 0;
        $failed = 0;
        
        $progressBar = $this->output->createProgressBar($truncatedNews->count());
        $progressBar->start();
        
        foreach ($truncatedNews as $news) {
            try {
                $this->info("\nProcessando ID {$news->id}: {$news->title}");
                
                // Tentar obter conteúdo completo
                $result = $newsApiService->getArticleDetails($news->title, $news->url);
                
                if ($result['success'] && !empty($result['article']['content'])) {
                    $newContent = $result['article']['content'];
                    
                    // Verificar se o novo conteúdo é significativamente maior
                    if (strlen($newContent) > strlen($news->content) * 2) {
                        $news->update(['content' => $newContent]);
                        $updated++;
                        $this->info("✅ Atualizada: " . strlen($news->content) . " → " . strlen($newContent) . " chars");
                    } else {
                        // Se não conseguiu conteúdo melhor, pelo menos limpar o [+chars]
                        $cleanedContent = preg_replace('/\s*\[+\d+\s*chars?\]\s*$/', '', $news->content);
                        if ($cleanedContent !== $news->content) {
                            $news->update(['content' => $cleanedContent]);
                            $updated++;
                            $this->info("✅ Limpa: removido [+chars]");
                        } else {
                            $failed++;
                            $this->info("❌ Falhou: não conseguiu melhorar conteúdo");
                        }
                    }
                } else {
                    // Se não conseguiu da API, pelo menos limpar o [+chars]
                    $cleanedContent = preg_replace('/\s*\[+\d+\s*chars?\]\s*$/', '', $news->content);
                    if ($cleanedContent !== $news->content) {
                        $news->update(['content' => $cleanedContent]);
                        $updated++;
                        $this->info("✅ Limpa: removido [+chars]");
                    } else {
                        $failed++;
                        $this->info("❌ Falhou: " . ($result['error'] ?? 'Erro desconhecido'));
                    }
                }
                
                usleep(500000); // 0.5 segundos
                
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
        $remainingTruncated = News::where('content', 'like', '%[+%chars%]%')->count();
        $this->info("📊 Notícias ainda com [+chars]: {$remainingTruncated}");
        
        if ($remainingTruncated > 0) {
            $this->info("\n💡 Execute: php artisan news:clean-truncated --limit={$remainingTruncated}");
        }
    }
}
