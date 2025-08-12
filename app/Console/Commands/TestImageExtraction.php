<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestImageExtraction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:test-image-extraction {id : ID da notícia para testar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a extração de imagens de uma notícia específica';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $news = News::find($id);
        
        if (!$news) {
            $this->error("Notícia ID {$id} não encontrada!");
            return;
        }

        $this->info("🔍 Testando extração de imagens para notícia ID: {$id}");
        $this->info("Título: {$news->title}");
        $this->info("URL: {$news->url}");
        $this->info("Imagem atual: {$news->url_to_image}");

        if (!$news->url) {
            $this->error("❌ Notícia não tem URL!");
            return;
        }

        $images = $this->extractImagesFromUrl($news->url);
        
        if (!empty($images)) {
            $this->info("🖼️  Imagens encontradas: " . count($images));
            $this->newLine();
            
            foreach ($images as $index => $imageUrl) {
                $this->info("Imagem " . ($index + 1) . ":");
                $this->line("   URL: {$imageUrl}");
                
                // Verificar se a imagem é acessível
                try {
                    $response = Http::timeout(10)->head($imageUrl);
                    if ($response->successful()) {
                        $this->line("   ✅ Acessível");
                    } else {
                        $this->line("   ❌ Não acessível (Status: " . $response->status() . ")");
                    }
                } catch (\Exception $e) {
                    $this->line("   ❌ Erro ao verificar: " . $e->getMessage());
                }
                $this->newLine();
            }
            
            // Sugerir melhor imagem
            $bestImage = $this->findBestImage($images);
            if ($bestImage) {
                $this->info("🎯 Melhor imagem sugerida: {$bestImage}");
            }
            
        } else {
            $this->warn("⚠️  Nenhuma imagem encontrada no artigo");
        }
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
                return $this->extractImagesFromHtml($html);
            }
            
        } catch (\Exception $e) {
            $this->error("Erro ao acessar URL: " . $e->getMessage());
        }
        
        return [];
    }

    private function extractImagesFromHtml($html)
    {
        $images = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);

        // Buscar todas as tags img
        $imgElements = $xpath->query('//img');
        
        foreach ($imgElements as $img) {
            $src = $img->getAttribute('src');
            $alt = $img->getAttribute('alt');
            $title = $img->getAttribute('title');
            $width = $img->getAttribute('width');
            $height = $img->getAttribute('height');
            
            if ($src && !empty($src)) {
                // Converter URLs relativas em absolutas se necessário
                if (strpos($src, 'http') !== 0) {
                    $src = $this->makeAbsoluteUrl($src, $url);
                }
                
                $images[] = [
                    'url' => $src,
                    'alt' => $alt,
                    'title' => $title,
                    'width' => $width,
                    'height' => $height,
                    'score' => $this->calculateImageScore($src, $alt, $title, $width, $height)
                ];
            }
        }
        
        // Ordenar por score (melhor primeiro)
        usort($images, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        return array_column($images, 'url');
    }

    private function calculateImageScore($src, $alt, $title, $width, $height)
    {
        $score = 0;
        
        // Pontuar por tamanho (imagens maiores são geralmente melhores)
        if ($width && $height) {
            $area = (int)$width * (int)$height;
            if ($area > 100000) $score += 10; // > 100k pixels
            elseif ($area > 50000) $score += 8; // > 50k pixels
            elseif ($area > 25000) $score += 6; // > 25k pixels
            elseif ($area > 10000) $score += 4; // > 10k pixels
        }
        
        // Pontuar por ter texto alternativo
        if (!empty($alt)) $score += 5;
        if (!empty($title)) $score += 3;
        
        // Pontuar por extensão de arquivo
        $extension = strtolower(pathinfo($src, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) $score += 2;
        
        // Penalizar imagens pequenas ou ícones
        if (strpos($src, 'icon') !== false) $score -= 5;
        if (strpos($src, 'logo') !== false) $score -= 3;
        if (strpos($src, 'avatar') !== false) $score -= 5;
        
        return $score;
    }

    private function findBestImage($images)
    {
        if (empty($images)) return null;
        
        // Retornar a primeira imagem (já ordenada por score)
        return $images[0];
    }

    private function makeAbsoluteUrl($relativeUrl, $baseUrl)
    {
        // Implementação básica para converter URLs relativas em absolutas
        if (strpos($relativeUrl, '//') === 0) {
            return 'https:' . $relativeUrl;
        }
        
        if (strpos($relativeUrl, '/') === 0) {
            $parsed = parse_url($baseUrl);
            return $parsed['scheme'] . '://' . $parsed['host'] . $relativeUrl;
        }
        
        return $relativeUrl;
    }
}
