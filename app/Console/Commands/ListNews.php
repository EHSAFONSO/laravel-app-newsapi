<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class ListNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:list {--limit=10 : Número de notícias para mostrar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista as notícias salvas no banco de dados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("Total de notícias no banco: " . News::count());
        $this->info("Mostrando as últimas {$limit} notícias:");
        $this->newLine();
        
        $news = News::latest()->take($limit)->get();
        
        if ($news->isEmpty()) {
            $this->warn('Nenhuma notícia encontrada no banco de dados.');
            return;
        }
        
        foreach ($news as $index => $item) {
            $this->line(($index + 1) . '. ' . $item->title);
            $this->line('   Categoria: ' . $item->category);
            $this->line('   Fonte: ' . $item->source_name);
            $this->line('   Data: ' . $item->published_at);
            $this->newLine();
        }
    }
}
