<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchCompleteContentFromUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-complete-from-urls {--id= : ID específico da notícia} {--all : Buscar todas as notícias com [+char]} {--limit=10 : Limite de notícias para processar} {--from= : ID inicial do range} {--to= : ID final do range}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca conteúdo completo de notícias usando apenas as URLs (sem consumir quota da API)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $specificId = $this->option('id');
        $fetchAll = $this->option('all');
        $limit = (int) $this->option('limit');
        $fromId = $this->option('from');
        $toId = $this->option('to');

        if ($specificId) {
            $this->fetchForSpecificNews($specificId);
        } elseif ($fromId && $toId) {
            $this->fetchForRange($fromId, $toId);
        } elseif ($fetchAll) {
            $this->fetchForAllNewsWithCharMarkers($limit);
        } else {
            $this->info('Use --id=ID para uma notícia específica');
            $this->info('Use --from=ID --to=ID para um range de IDs');
            $this->info('Use --all para todas as notícias com [+char]');
            $this->info('Exemplo: php artisan news:fetch-complete-from-urls --id=73');
            $this->info('Exemplo: php artisan news:fetch-complete-from-urls --from=50 --to=100');
            $this->info('Exemplo: php artisan news:fetch-complete-from-urls --all --limit=20');
        }
    }

    private function fetchForSpecificNews($id)
    {
        $news = News::find($id);
        
        if (!$news) {
            $this->error("Notícia ID {$id} não encontrada!");
            return;
        }

        $this->info("🔍 Buscando conteúdo completo para notícia ID: {$id}");
        $this->info("Título: {$news->title}");
        $this->info("URL: {$news->url}");
        $this->info("Conteúdo atual: " . strlen($news->content) . " chars");

        if (!$news->url) {
            $this->error("❌ Notícia não tem URL para buscar conteúdo completo!");
            return;
        }

        $result = $this->fetchContentFromUrl($news->url);
        
        if ($result['success']) {
            $news->content = $result['content'];
            
            // Salvar imagens encontradas
            if (!empty($result['images'])) {
                $this->info("🖼️  Imagens encontradas: " . count($result['images']));
                foreach ($result['images'] as $index => $imageUrl) {
                    $this->line("   • Imagem " . ($index + 1) . ": {$imageUrl}");
                }
                
                // Salvar a primeira imagem como imagem principal se não houver uma
                if (empty($news->url_to_image) && !empty($result['images'][0])) {
                    $news->url_to_image = $result['images'][0];
                    $this->info("   ✅ Imagem principal atualizada");
                }
            }
            
            $news->save();
            
            $this->info("✅ Conteúdo atualizado! Novo tamanho: " . strlen($result['content']) . " chars");
            $this->info("Primeiros 200 caracteres:");
            $this->line(substr($result['content'], 0, 200) . "...");
        } else {
            $this->error("❌ Erro ao buscar conteúdo: " . $result['error']);
        }
    }

    private function fetchForRange($fromId, $toId)
    {
        $newsWithChar = News::where('content', 'LIKE', '%[+char]%')
            ->whereNotNull('url')
            ->where('url', '!=', '')
            ->whereBetween('id', [$fromId, $toId])
            ->get();

        if ($newsWithChar->isEmpty()) {
            $this->info("✅ Nenhuma notícia com [+char] e URL válida encontrada no range ID {$fromId}-{$toId}!");
            return;
        }

        $this->info("🔍 Encontradas {$newsWithChar->count()} notícias com [+char] e URL válida no range ID {$fromId}-{$toId}");
        $this->newLine();

        $successCount = 0;
        $errorCount = 0;

        foreach ($newsWithChar as $news) {
            $this->info("Processando ID {$news->id}: {$news->title}");
            
            $result = $this->fetchContentFromUrl($news->url);
            
            if ($result['success']) {
                $oldLength = strlen($news->content);
                $news->content = $result['content'];
                
                // Salvar imagens encontradas
                if (!empty($result['images'])) {
                    $this->info("  🖼️  Imagens encontradas: " . count($result['images']));
                    
                    // Salvar a primeira imagem como imagem principal se não houver uma
                    if (empty($news->url_to_image) && !empty($result['images'][0])) {
                        $news->url_to_image = $result['images'][0];
                        $this->info("  ✅ Imagem principal atualizada");
                    }
                }
                
                $news->save();
                
                $newLength = strlen($result['content']);
                $this->info("  ✅ Atualizado: {$oldLength} → {$newLength} chars");
                $successCount++;
            } else {
                $this->error("  ❌ Erro: " . $result['error']);
                $errorCount++;
            }
            
            // Pequena pausa para não sobrecarregar os servidores
            sleep(1);
        }

        $this->newLine();
        $this->info("🎉 Processamento concluído para range ID {$fromId}-{$toId}!");
        $this->info("✅ Sucessos: {$successCount}");
        $this->info("❌ Erros: {$errorCount}");
    }

    private function fetchForAllNewsWithCharMarkers($limit)
    {
        $newsWithChar = News::where('content', 'LIKE', '%[+char]%')
            ->whereNotNull('url')
            ->where('url', '!=', '')
            ->limit($limit)
            ->get();

        if ($newsWithChar->isEmpty()) {
            $this->info("✅ Nenhuma notícia com [+char] e URL válida encontrada!");
            return;
        }

        $this->info("🔍 Encontradas {$newsWithChar->count()} notícias com [+char] e URL válida");
        $this->newLine();

        $successCount = 0;
        $errorCount = 0;

        foreach ($newsWithChar as $news) {
            $this->info("Processando ID {$news->id}: {$news->title}");
            
            $result = $this->fetchContentFromUrl($news->url);
            
            if ($result['success']) {
                $oldLength = strlen($news->content);
                $news->content = $result['content'];
                $news->save();
                
                $newLength = strlen($result['content']);
                $this->info("  ✅ Atualizado: {$oldLength} → {$newLength} chars");
                $successCount++;
            } else {
                $this->error("  ❌ Erro: " . $result['error']);
                $errorCount++;
            }
            
            // Pequena pausa para não sobrecarregar os servidores
            sleep(1);
        }

        $this->newLine();
        $this->info("🎉 Processamento concluído!");
        $this->info("✅ Sucessos: {$successCount}");
        $this->info("❌ Erros: {$errorCount}");
    }

    private function fetchContentFromUrl($url)
    {
        try {
            Log::info("Tentando buscar conteúdo completo de: {$url}");
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ])
                ->get($url);
            
            if ($response->successful()) {
                $html = $response->body();
                
                // Remover scripts e estilos
                $html = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $html);
                $html = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/mi', '', $html);
                
                // Extrair conteúdo
                $content = $this->extractArticleContent($html);
                
                // Extrair imagens
                $images = $this->extractArticleImages($html);
                
                if ($content && strlen($content) > 500) {
                    Log::info("Conteúdo extraído com sucesso: " . strlen($content) . " chars");
                    Log::info("Imagens encontradas: " . count($images));
                    
                    return [
                        'success' => true,
                        'content' => $content,
                        'images' => $images
                    ];
                }
            }
            
            Log::warning("Não foi possível extrair conteúdo de: {$url}");
            return [
                'success' => false,
                'error' => 'Não foi possível extrair o conteúdo do artigo'
            ];
            
        } catch (\Exception $e) {
            Log::error("Erro ao buscar conteúdo completo: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erro ao buscar conteúdo: ' . $e->getMessage()
            ];
        }
    }

    private function extractArticleContent($html)
    {
        // Criar um DOMDocument para parsing
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);
        
        // Seletores XPath para conteúdo de artigos
        $selectors = [
            '//article',
            '//*[contains(@class, "article")]',
            '//*[contains(@class, "content")]',
            '//*[contains(@class, "post")]',
            '//*[contains(@class, "story")]',
            '//*[@class="entry-content"]',
            '//*[@class="post-content"]',
            '//*[@class="article-content"]',
            '//*[@class="story-content"]',
            '//*[@class="content-body"]',
            '//*[@class="post-body"]',
            '//*[@class="article-body"]',
            '//*[@class="story-body"]',
            '//main',
            '//*[@class="main-content"]',
            '//*[@class="main-article"]',
            '//*[@class="article-text"]',
            '//*[@class="story-text"]',
            '//*[@class="post-text"]'
        ];
        
        foreach ($selectors as $selector) {
            try {
                $elements = $xpath->query($selector);
                
                if ($elements && $elements->length > 0) {
                    $content = '';
                    foreach ($elements as $element) {
                        $content .= $this->extractTextWithParagraphs($element);
                    }
                    
                    if (strlen($content) > 500) {
                        return trim($content);
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        // Fallback: tentar extrair de tags p
        try {
            $paragraphs = $xpath->query('//p');
            if ($paragraphs && $paragraphs->length > 0) {
                $content = '';
                foreach ($paragraphs as $p) {
                    $text = trim($p->textContent);
                    if (strlen($text) > 30) {
                        $content .= $text . "\n\n";
                    }
                }
                
                if (strlen($content) > 500) {
                    return trim($content);
                }
            }
        } catch (\Exception $e) {
            // Ignorar erros de XPath
        }
        
        return null;
    }

    private function extractArticleImages($html)
    {
        $images = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);

        // Seletores XPath para imagens
        $selectors = [
            '//img',
            '//*[contains(@class, "image")]',
            '//*[contains(@class, "photo")]',
            '//*[contains(@class, "figure")]',
            '//*[@class="wp-caption"]',
            '//*[@class="wp-block-image"]',
            '//*[@class="aligncenter"]',
            '//*[@class="alignleft"]',
            '//*[@class="alignright"]'
        ];

        foreach ($selectors as $selector) {
            try {
                $element = $xpath->query($selector)->item(0);
                if ($element) {
                    $src = $element->getAttribute('src');
                    if ($src) {
                        $images[] = $src;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        return array_unique($images); // Remover duplicatas
    }

    private function extractTextWithParagraphs($element)
    {
        $content = '';
        
        foreach ($element->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $text = trim($child->textContent);
                if (!empty($text)) {
                    $content .= $text . ' ';
                }
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $tagName = strtolower($child->tagName);
                
                switch ($tagName) {
                    case 'p':
                        $text = trim($child->textContent);
                        if (!empty($text)) {
                            $content .= $text . "\n\n";
                        }
                        break;
                        
                    case 'br':
                        $content .= "\n";
                        break;
                        
                    case 'h1':
                    case 'h2':
                    case 'h3':
                    case 'h4':
                    case 'h5':
                    case 'h6':
                        $text = trim($child->textContent);
                        if (!empty($text)) {
                            $content .= $text . "\n\n";
                        }
                        break;
                        
                    case 'div':
                    case 'section':
                    case 'article':
                        $content .= $this->extractTextWithParagraphs($child);
                        break;
                        
                    case 'ul':
                    case 'ol':
                        foreach ($child->childNodes as $li) {
                            if ($li->nodeType === XML_ELEMENT_NODE && strtolower($li->tagName) === 'li') {
                                $text = trim($li->textContent);
                                if (!empty($text)) {
                                    $content .= "• " . $text . "\n";
                                }
                            }
                        }
                        $content .= "\n";
                        break;
                        
                    case 'blockquote':
                        $text = trim($child->textContent);
                        if (!empty($text)) {
                            $content .= '"' . $text . '"\n\n';
                        }
                        break;
                        
                    default:
                        $text = trim($child->textContent);
                        if (!empty($text)) {
                            $content .= $text . ' ';
                        }
                        break;
                }
            }
        }
        
        return $content;
    }
}
