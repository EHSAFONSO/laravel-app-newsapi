<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Models\NewsImage;

class ListNewsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:list-images {--id= : ID específico da notícia} {--from= : ID inicial do range} {--to= : ID final do range} {--type= : Tipo de imagem (main, content, gallery)} {--limit=10 : Limite de notícias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista as imagens das notícias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $specificId = $this->option('id');
        $fromId = $this->option('from');
        $toId = $this->option('to');
        $type = $this->option('type');
        $limit = (int) $this->option('limit');

        if ($specificId) {
            $this->listSpecificNewsImages($specificId, $type);
        } elseif ($fromId && $toId) {
            $this->listRangeImages($fromId, $toId, $type, $limit);
        } else {
            $this->listAllImages($type, $limit);
        }
    }

    private function listSpecificNewsImages($id, $type)
    {
        $news = News::with('images')->find($id);
        
        if (!$news) {
            $this->error("Notícia ID {$id} não encontrada!");
            return;
        }

        $this->displayNewsImages($news, $type);
    }

    private function listRangeImages($fromId, $toId, $type, $limit)
    {
        $newsList = News::with('images')
            ->whereBetween('id', [$fromId, $toId])
            ->limit($limit)
            ->get();

        if ($newsList->isEmpty()) {
            $this->info("✅ Nenhuma notícia encontrada no range ID {$fromId}-{$toId}!");
            return;
        }

        $this->info("🔍 Listando imagens de {$newsList->count()} notícias no range ID {$fromId}-{$toId}");
        $this->newLine();

        foreach ($newsList as $news) {
            $this->displayNewsImages($news, $type);
            $this->newLine();
        }
    }

    private function listAllImages($type, $limit)
    {
        $newsList = News::with('images')
            ->limit($limit)
            ->get();

        if ($newsList->isEmpty()) {
            $this->info("✅ Nenhuma notícia encontrada!");
            return;
        }

        $this->info("🔍 Listando imagens de {$newsList->count()} notícias");
        $this->newLine();

        foreach ($newsList as $news) {
            $this->displayNewsImages($news, $type);
            $this->newLine();
        }
    }

    private function displayNewsImages($news, $type)
    {
        $this->info("📰 Notícia ID {$news->id}: {$news->title}");
        $this->line("   URL: {$news->url}");
        $this->line("   Imagem original: {$news->url_to_image}");
        
        $images = $news->images;
        
        if ($type) {
            $images = $images->where('type', $type);
        }

        if ($images->isEmpty()) {
            $this->warn("   ⚠️  Nenhuma imagem encontrada" . ($type ? " do tipo '{$type}'" : ""));
            return;
        }

        $this->info("   🖼️  Imagens encontradas: {$images->count()}");
        
        foreach ($images as $image) {
            $status = $image->is_accessible ? "✅" : "❌";
            $this->line("   {$status} [{$image->type}] {$image->url}");
            $this->line("      Score: {$image->score}, Tamanho: {$image->width}x{$image->height}");
            if ($image->alt_text) {
                $this->line("      Alt: {$image->alt_text}");
            }
        }
    }
}
