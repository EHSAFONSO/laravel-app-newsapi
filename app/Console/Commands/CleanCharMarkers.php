<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Illuminate\Support\Facades\DB;

class CleanCharMarkers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:char-markers {--dry-run : Executar apenas simulação sem fazer alterações} {--field=all : Campo específico para limpar (content, title, description, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa marcadores [+char] das notícias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $field = $this->option('field');
        
        $this->info('🔍 Analisando notícias com marcadores [+char]...');
        
        // Contar notícias com [+char] em diferentes campos
        $contentCount = News::where('content', 'LIKE', '%[+char]%')->count();
        $titleCount = News::where('title', 'LIKE', '%[+char]%')->count();
        $descriptionCount = News::where('description', 'LIKE', '%[+char]%')->count();
        
        $this->info("📊 Estatísticas encontradas:");
        $this->line("   • Conteúdo: {$contentCount} notícias");
        $this->line("   • Título: {$titleCount} notícias");
        $this->line("   • Descrição: {$descriptionCount} notícias");
        
        if ($contentCount === 0 && $titleCount === 0 && $descriptionCount === 0) {
            $this->info('✅ Nenhuma notícia com marcadores [+char] encontrada!');
            return;
        }
        
        // Mostrar algumas notícias como exemplo
        if ($contentCount > 0) {
            $this->newLine();
            $this->info('📝 Exemplos de notícias com [+char] no conteúdo:');
            $examples = News::where('content', 'LIKE', '%[+char]%')
                ->select('id', 'title', 'source_name')
                ->limit(5)
                ->get();
                
            foreach ($examples as $example) {
                $this->line("   • ID {$example->id}: {$example->title} ({$example->source_name})");
            }
        }
        
        if ($isDryRun) {
            $this->newLine();
            $this->warn('🔍 MODO SIMULAÇÃO - Nenhuma alteração será feita');
            $this->info('Execute sem --dry-run para aplicar as limpezas');
            return;
        }
        
        $this->newLine();
        if (!$this->confirm('Deseja continuar com a limpeza dos marcadores [+char]?')) {
            $this->info('❌ Operação cancelada');
            return;
        }
        
        $this->info('🧹 Iniciando limpeza dos marcadores [+char]...');
        
        $updatedCount = 0;
        
        // Limpar conteúdo
        if ($field === 'all' || $field === 'content') {
            if ($contentCount > 0) {
                $this->line('   Limpando conteúdo...');
                $contentUpdated = News::where('content', 'LIKE', '%[+char]%')
                    ->update(['content' => DB::raw("REPLACE(content, '[+char]', '')")]);
                $updatedCount += $contentUpdated;
                $this->info("   ✅ {$contentUpdated} notícias atualizadas no conteúdo");
            }
        }
        
        // Limpar título
        if ($field === 'all' || $field === 'title') {
            if ($titleCount > 0) {
                $this->line('   Limpando títulos...');
                $titleUpdated = News::where('title', 'LIKE', '%[+char]%')
                    ->update(['title' => DB::raw("REPLACE(title, '[+char]', '')")]);
                $updatedCount += $titleUpdated;
                $this->info("   ✅ {$titleUpdated} notícias atualizadas no título");
            }
        }
        
        // Limpar descrição
        if ($field === 'all' || $field === 'description') {
            if ($descriptionCount > 0) {
                $this->line('   Limpando descrições...');
                $descriptionUpdated = News::where('description', 'LIKE', '%[+char]%')
                    ->update(['description' => DB::raw("REPLACE(description, '[+char]', '')")]);
                $updatedCount += $descriptionUpdated;
                $this->info("   ✅ {$descriptionUpdated} notícias atualizadas na descrição");
            }
        }
        
        $this->newLine();
        $this->info("🎉 Limpeza concluída! {$updatedCount} notícias foram atualizadas.");
        
        // Verificar se ainda há marcadores
        $remainingContent = News::where('content', 'LIKE', '%[+char]%')->count();
        $remainingTitle = News::where('title', 'LIKE', '%[+char]%')->count();
        $remainingDescription = News::where('description', 'LIKE', '%[+char]%')->count();
        
        if ($remainingContent > 0 || $remainingTitle > 0 || $remainingDescription > 0) {
            $this->warn('⚠️  Ainda existem marcadores [+char] em algumas notícias:');
            $this->line("   • Conteúdo: {$remainingContent}");
            $this->line("   • Título: {$remainingTitle}");
            $this->line("   • Descrição: {$remainingDescription}");
            $this->info('Execute o comando novamente se necessário.');
        } else {
            $this->info('✅ Todos os marcadores [+char] foram removidos com sucesso!');
        }
    }
}
