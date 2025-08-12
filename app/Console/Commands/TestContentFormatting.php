<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class TestContentFormatting extends Command
{
    protected $signature = 'news:test-formatting {id?}';
    protected $description = 'Test the content formatting for a specific news article';

    public function handle()
    {
        $id = $this->argument('id') ?? 1;
        
        $news = News::find($id);
        if (!$news) {
            $this->error("Notícia com ID {$id} não encontrada!");
            return;
        }
        
        $this->info("Testando formatação para notícia ID: {$id}");
        $this->info("Título: {$news->title}");
        $this->info("Conteúdo: " . strlen($news->content ?? '') . " caracteres");
        
        if (empty($news->content)) {
            $this->warn("Notícia não tem conteúdo!");
            return;
        }
        
        $this->info("\n=== CONTEÚDO ORIGINAL ===");
        $this->info(substr($news->content, 0, 500) . "...");
        
        $this->info("\n=== ANÁLISE DE FORMATAÇÃO ===");
        
        // Contar quebras de linha
        $lineBreaks = substr_count($news->content, "\n");
        $doubleLineBreaks = substr_count($news->content, "\n\n");
        $paragraphs = $doubleLineBreaks + 1;
        
        $this->info("Quebras de linha simples: {$lineBreaks}");
        $this->info("Quebras de linha duplas: {$doubleLineBreaks}");
        $this->info("Parágrafos estimados: {$paragraphs}");
        
        // Verificar se há listas
        $hasBullets = strpos($news->content, '•') !== false;
        $this->info("Contém listas com bullets: " . ($hasBullets ? 'SIM' : 'NÃO'));
        
        // Verificar se há citações
        $hasQuotes = substr_count($news->content, '"') >= 2;
        $this->info("Contém citações: " . ($hasQuotes ? 'SIM' : 'NÃO'));
        
        // Mostrar exemplo de formatação
        $this->info("\n=== EXEMPLO DE FORMATAÇÃO HTML ===");
        $formatted = $this->formatContentForDisplay($news->content);
        $this->info(substr($formatted, 0, 300) . "...");
        
        $this->info("\n✅ Análise concluída!");
    }
    
    private function formatContentForDisplay($content)
    {
        if (empty($content)) return '';
        
        // Preservar quebras de linha duplas (parágrafos)
        $formatted = $content;
        $formatted = str_replace("\n\n", '</p><p>', $formatted); // Quebras duplas viram parágrafos
        $formatted = str_replace("\n", '<br>', $formatted); // Quebras simples viram <br>
        
        // Envolver em tags de parágrafo
        $formatted = '<p>' . $formatted . '</p>';
        
        // Limpar parágrafos vazios
        $formatted = str_replace('<p></p>', '', $formatted);
        
        // Formatar listas com bullets
        $formatted = str_replace('• ', '<br>• ', $formatted);
        
        // Formatar citações
        $formatted = preg_replace('/"([^"]+)"/', '<blockquote class="border-l-4 border-gray-300 pl-4 italic my-4">$1</blockquote>', $formatted);
        
        return $formatted;
    }
}
