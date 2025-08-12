<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsApiService;
use App\Models\News;

class FetchCompleteNews extends Command
{
    protected $signature = 'news:fetch-complete {--limit=20} {--category=general}';
    protected $description = 'Faz uma nova requisição completa e atualiza o banco com conteúdos completos';

    public function handle()
    {
        $limit = $this->option('limit');
        $category = $this->option('category');
        
        $this->info("Fazendo nova requisição completa para categoria: {$category} (limite: {$limit})...");
        
        // Verificar se a API está funcionando
        $newsApiService = app(NewsApiService::class);
        
        try {
            // Tentar buscar notícias da API
            $this->info("Testando API...");
            $testResult = $newsApiService->getTopHeadlines('us', 1, 1);
            
            if (!$testResult['success']) {
                $this->error("❌ API não está disponível: " . ($testResult['error'] ?? 'Erro desconhecido'));
                $this->info("💡 Aguarde algumas horas para o limite da API resetar");
                return;
            }
            
            $this->info("✅ API funcionando! Buscando notícias...");
            
            // Buscar notícias da categoria
            $news = $newsApiService->getNewsByCategory($category, 'us', 1, $limit);
            
            if (!$news['success'] || empty($news['articles'])) {
                $this->error("❌ Não foi possível buscar notícias da categoria {$category}");
                return;
            }
            
            $this->info("📰 Encontradas " . count($news['articles']) . " notícias");
            
            $updated = 0;
            $failed = 0;
            
            $progressBar = $this->output->createProgressBar(count($news['articles']));
            $progressBar->start();
            
            foreach ($news['articles'] as $article) {
                try {
                    $this->info("\nProcessando: {$article['title']}");
                    
                    // Verificar se a notícia já existe no banco
                    $existingNews = News::where('title', $article['title'])->first();
                    
                    if ($existingNews) {
                        $this->info("   Notícia já existe (ID: {$existingNews->id})");
                        
                        // Tentar obter conteúdo completo se não temos
                        if (empty($existingNews->content) || strlen($existingNews->content) < 500) {
                            $this->info("   Buscando conteúdo completo...");
                            $result = $newsApiService->getArticleDetails($article['title'], $article['url']);
                            
                            if ($result['success'] && !empty($result['article']['content'])) {
                                $existingNews->update(['content' => $result['article']['content']]);
                                $updated++;
                                $this->info("   ✅ Conteúdo atualizado: " . strlen($result['article']['content']) . " chars");
                            } else {
                                $failed++;
                                $this->info("   ❌ Falhou: " . ($result['error'] ?? 'Erro desconhecido'));
                            }
                        } else {
                            $this->info("   ✅ Já tem conteúdo adequado: " . strlen($existingNews->content) . " chars");
                        }
                    } else {
                        $this->info("   Nova notícia, salvando...");
                        
                        // Tentar obter conteúdo completo antes de salvar
                        $result = $newsApiService->getArticleDetails($article['title'], $article['url']);
                        
                        $content = null;
                        if ($result['success'] && !empty($result['article']['content'])) {
                            $content = $result['article']['content'];
                            $this->info("   ✅ Conteúdo obtido: " . strlen($content) . " chars");
                        } else {
                            $this->info("   ⚠️ Conteúdo não disponível, salvando sem conteúdo");
                        }
                        
                        // Salvar no banco
                        News::create([
                            'title' => $article['title'] ?? 'Sem título',
                            'description' => $article['description'] ?? 'Sem descrição',
                            'url' => $article['url'] ?? '#',
                            'url_to_image' => $article['urlToImage'] ?? null,
                            'published_at' => $article['publishedAt'] ?? now(),
                            'source_name' => $article['source']['name'] ?? 'Fonte desconhecida',
                            'category' => $category,
                            'content' => $content,
                            'author' => $article['author'] ?? null
                        ]);
                        
                        $updated++;
                    }
                    
                    usleep(1000000); // 1 segundo entre requisições
                    
                } catch (\Exception $e) {
                    $failed++;
                    $this->error("   ❌ Erro: " . $e->getMessage());
                }
                
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->newLine();
            
            $this->info("✅ Atualizadas: {$updated} notícias");
            $this->info("❌ Falharam: {$failed} notícias");
            
            // Verificar resultado final
            $totalNews = News::count();
            $newsWithContent = News::whereNotNull('content')->where('content', '!=', '')->where('content', '!=', 'null')->count();
            $newsWithTruncated = News::where('content', 'like', '%[%+%chars]%')->count();
            
            $this->info("\n📊 Estatísticas finais:");
            $this->info("   Total de notícias: {$totalNews}");
            $this->info("   Com conteúdo: {$newsWithContent}");
            $this->info("   Com [+chars]: {$newsWithTruncated}");
            $this->info("   Percentual com conteúdo: " . round(($newsWithContent / $totalNews) * 100, 1) . "%");
            
        } catch (\Exception $e) {
            $this->error("❌ Erro geral: " . $e->getMessage());
        }
    }
}
