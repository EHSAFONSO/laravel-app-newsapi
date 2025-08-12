<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateNewsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:update-images {--id= : ID específico da notícia} {--from= : ID inicial do range} {--to= : ID final do range} {--all : Processar todas as notícias} {--limit=50 : Limite de notícias para processar} {--dry-run : Executar apenas simulação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza as imagens das notícias extraindo do conteúdo dos artigos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $specificId = $this->option('id');
        $fromId = $this->option('from');
        $toId = $this->option('to');
        $processAll = $this->option('all');
        $limit = (int) $this->option('limit');
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('🔍 MODO SIMULAÇÃO - Nenhuma alteração será feita no banco');
        }

        if ($specificId) {
            $this->processSpecificNews($specificId, $isDryRun);
        } elseif ($fromId && $toId) {
            $this->processRange($fromId, $toId, $isDryRun);
        } elseif ($processAll) {
            $this->processAllNews($limit, $isDryRun);
        } else {
            $this->showUsage();
        }
    }

    private function processSpecificNews($id, $isDryRun)
    {
        $news = News::find($id);
        
        if (!$news) {
            $this->error("Notícia ID {$id} não encontrada!");
            return;
        }

        $this->info("🔍 Processando notícia ID: {$id}");
        $this->info("Título: {$news->title}");
        $this->info("URL: {$news->url}");

        if (!$news->url) {
            $this->error("❌ Notícia não tem URL!");
            return;
        }

        $this->extractAndSaveImages($news, $isDryRun);
    }

    private function processRange($fromId, $toId, $isDryRun)
    {
        $newsList = News::whereBetween('id', [$fromId, $toId])
            ->whereNotNull('url')
            ->where('url', '!=', '')
            ->get();

        if ($newsList->isEmpty()) {
            $this->info("✅ Nenhuma notícia encontrada no range ID {$fromId}-{$toId}!");
            return;
        }

        $this->info("🔍 Processando {$newsList->count()} notícias no range ID {$fromId}-{$toId}");
        $this->processNewsList($newsList, $isDryRun);
    }

    private function processAllNews($limit, $isDryRun)
    {
        $newsList = News::whereNotNull('url')
            ->where('url', '!=', '')
            ->limit($limit)
            ->get();

        if ($newsList->isEmpty()) {
            $this->info("✅ Nenhuma notícia com URL válida encontrada!");
            return;
        }

        $this->info("🔍 Processando {$newsList->count()} notícias");
        $this->processNewsList($newsList, $isDryRun);
    }

    private function processNewsList($newsList, $isDryRun)
    {
        $successCount = 0;
        $errorCount = 0;
        $totalImages = 0;

        foreach ($newsList as $news) {
            $this->info("Processando ID {$news->id}: {$news->title}");
            
            try {
                $imageCount = $this->extractAndSaveImages($news, $isDryRun);
                if ($imageCount > 0) {
                    $successCount++;
                    $totalImages += $imageCount;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Erro: " . $e->getMessage());
                $errorCount++;
            }

            sleep(1); // Pausa para não sobrecarregar
        }

        $this->newLine();
        $this->info("🎉 Processamento concluído!");
        $this->info("✅ Sucessos: {$successCount}");
        $this->info("❌ Erros: {$errorCount}");
        $this->info("🖼️  Total de imagens: {$totalImages}");
    }

    private function extractAndSaveImages($news, $isDryRun)
    {
        $images = $this->extractImagesFromUrl($news->url);
        
        if (empty($images)) {
            $this->warn("  ⚠️  Nenhuma imagem encontrada");
            return 0;
        }

        $this->info("  🖼️  Encontradas " . count($images) . " imagens");

        if ($isDryRun) {
            foreach ($images as $index => $image) {
                $this->line("    • Imagem " . ($index + 1) . ": {$image['url']} (Score: {$image['score']})");
            }
            return count($images);
        }

        // Salvar imagens no banco
        $savedCount = 0;
        foreach ($images as $image) {
            try {
                $newsImage = $news->addImage([
                    'url' => $image['url'],
                    'alt_text' => $image['alt'],
                    'title' => $image['title'],
                    'width' => $image['width'],
                    'height' => $image['height'],
                    'score' => $image['score'],
                    'type' => $savedCount === 0 ? 'main' : 'content', // Primeira imagem como principal
                    'is_accessible' => $image['is_accessible']
                ]);

                if ($savedCount === 0) {
                    $this->info("    ✅ Imagem principal salva: {$image['url']}");
                } else {
                    $this->line("    • Imagem salva: {$image['url']}");
                }

                $savedCount++;
            } catch (\Exception $e) {
                $this->error("    ❌ Erro ao salvar imagem: " . $e->getMessage());
            }
        }

        // Atualizar url_to_image se não existir
        if (empty($news->url_to_image) && $savedCount > 0) {
            $news->update(['url_to_image' => $images[0]['url']]);
            $this->info("    ✅ URL da imagem principal atualizada");
        }

        return $savedCount;
    }

    private function extractImagesFromUrl($url)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ])
                ->get($url);
            
            if ($response->successful()) {
                $html = $response->body();
                return $this->extractImagesFromHtml($html, $url);
            }
            
        } catch (\Exception $e) {
            Log::error("Erro ao extrair imagens de {$url}: " . $e->getMessage());
        }
        
        return [];
    }

    private function extractImagesFromHtml($html, $baseUrl)
    {
        $images = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);

        $imgElements = $xpath->query('//img');
        
        foreach ($imgElements as $img) {
            $src = $img->getAttribute('src');
            $alt = $img->getAttribute('alt');
            $title = $img->getAttribute('title');
            $width = $img->getAttribute('width');
            $height = $img->getAttribute('height');
            
            if ($src && !empty($src)) {
                // Converter URL relativa em absoluta
                if (strpos($src, 'http') !== 0) {
                    $src = $this->makeAbsoluteUrl($src, $baseUrl);
                }
                
                $score = $this->calculateImageScore($src, $alt, $title, $width, $height);
                $isAccessible = $this->checkImageAccessibility($src);
                
                $images[] = [
                    'url' => $src,
                    'alt' => $alt,
                    'title' => $title,
                    'width' => $width ? (int)$width : null,
                    'height' => $height ? (int)$height : null,
                    'score' => $score,
                    'is_accessible' => $isAccessible
                ];
            }
        }
        
        // Ordenar por score (melhor primeiro)
        usort($images, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        return $images;
    }

    private function calculateImageScore($src, $alt, $title, $width, $height)
    {
        $score = 0;
        
        // Pontuar por tamanho
        if ($width && $height) {
            $area = (int)$width * (int)$height;
            if ($area > 100000) $score += 10;
            elseif ($area > 50000) $score += 8;
            elseif ($area > 25000) $score += 6;
            elseif ($area > 10000) $score += 4;
        }
        
        // Pontuar por texto alternativo
        if (!empty($alt)) $score += 5;
        if (!empty($title)) $score += 3;
        
        // Pontuar por extensão
        $extension = strtolower(pathinfo($src, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) $score += 2;
        
        // Penalizar ícones/logos
        if (strpos($src, 'icon') !== false) $score -= 5;
        if (strpos($src, 'logo') !== false) $score -= 3;
        if (strpos($src, 'avatar') !== false) $score -= 5;
        
        return $score;
    }

    private function checkImageAccessibility($url)
    {
        try {
            $response = Http::timeout(10)->head($url);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function makeAbsoluteUrl($relativeUrl, $baseUrl)
    {
        if (strpos($relativeUrl, '//') === 0) {
            return 'https:' . $relativeUrl;
        }
        
        if (strpos($relativeUrl, '/') === 0) {
            $parsed = parse_url($baseUrl);
            return $parsed['scheme'] . '://' . $parsed['host'] . $relativeUrl;
        }
        
        return $relativeUrl;
    }

    private function showUsage()
    {
        $this->info('Uso do comando:');
        $this->line('  php artisan news:update-images --id=73');
        $this->line('  php artisan news:update-images --from=50 --to=100');
        $this->line('  php artisan news:update-images --all --limit=100');
        $this->line('  php artisan news:update-images --all --dry-run');
    }
}
