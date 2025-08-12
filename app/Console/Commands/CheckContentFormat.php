<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class CheckContentFormat extends Command
{
    protected $signature = 'news:check-format {id}';
    protected $description = 'Verifica a formatação do conteúdo de uma notícia específica';

    public function handle()
    {
        $id = $this->argument('id');
        
        $news = News::find($id);
        if (!$news) {
            $this->error("Notícia com ID {$id} não encontrada!");
            return;
        }
        
        $this->info("Verificando formatação da notícia ID: {$id}");
        $this->info("Título: {$news->title}");
        $this->info("Tamanho do conteúdo: " . strlen($news->content) . " caracteres");
        
        if (empty($news->content)) {
            $this->warn("Notícia sem conteúdo!");
            return;
        }
        
        $this->info("\nPrimeiros 500 caracteres do conteúdo:");
        $this->line("----------------------------------------");
        $this->line(substr($news->content, 0, 500));
        $this->line("----------------------------------------");
        
        // Verificar se há quebras de linha
        $lineBreaks = substr_count($news->content, "\n");
        $doubleLineBreaks = substr_count($news->content, "\n\n");
        
        $this->info("\nAnálise de formatação:");
        $this->info("   Quebras de linha simples: {$lineBreaks}");
        $this->info("   Quebras de linha duplas: {$doubleLineBreaks}");
        
        // Verificar se há parágrafos
        if ($doubleLineBreaks > 0) {
            $this->info("✅ Contém parágrafos (quebras duplas)");
        } else {
            $this->warn("⚠️ Não contém parágrafos (apenas texto contínuo)");
        }
        
        // Verificar se há listas
        if (strpos($news->content, '•') !== false) {
            $this->info("✅ Contém listas (bullets)");
        }
        
        // Verificar se há citações
        if (strpos($news->content, '"') !== false) {
            $this->info("✅ Contém citações");
        }
    }
}
