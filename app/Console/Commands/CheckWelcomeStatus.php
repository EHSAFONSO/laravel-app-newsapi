<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class CheckWelcomeStatus extends Command
{
    protected $signature = 'welcome:check';
    protected $description = 'Verifica o status da tela inicial';

    public function handle()
    {
        $this->info('Verificando status da tela inicial...');
        
        // Verificar se há notícias no banco
        $totalNews = News::count();
        $this->info("Total de notícias no banco: {$totalNews}");
        
        // Verificar notícias por categoria
        $techCount = News::where('category', 'technology')->count();
        $businessCount = News::where('category', 'business')->count();
        $healthCount = News::where('category', 'health')->count();
        
        $this->info("Notícias de tecnologia: {$techCount}");
        $this->info("Notícias de business: {$businessCount}");
        $this->info("Notícias de health: {$healthCount}");
        
        // Verificar se há notícias com problemas
        $truncatedCount = News::where('description', 'like', '%…%')->count();
        $this->info("Notícias com descrição truncada: {$truncatedCount}");
        
        if ($truncatedCount > 0) {
            $this->warn("⚠️ Ainda há {$truncatedCount} notícias com descrições truncadas");
        } else {
            $this->info("✅ Todas as descrições estão limpas");
        }
        
        $this->info("\n✅ Verificação concluída!");
    }
}
