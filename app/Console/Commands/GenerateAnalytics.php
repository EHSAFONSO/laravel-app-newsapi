<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\DB;

class GenerateAnalytics extends Command
{
    protected $signature = 'analytics:generate {--format=table : Output format (table, json, csv)}';
    protected $description = 'Generate analytics and insights about the news application';

    public function handle()
    {
        $this->info('📊 Gerando Analytics do Portal de Notícias...');
        
        $format = $this->option('format');
        
        // Estatísticas gerais
        $stats = $this->getGeneralStats();
        $this->displayStats($stats, $format);
        
        // Top categorias
        $topCategories = $this->getTopCategories();
        $this->displayTopCategories($topCategories, $format);
        
        // Top fontes
        $topSources = $this->getTopSources();
        $this->displayTopSources($topSources, $format);
        
        // Buscas mais populares
        $topSearches = $this->getTopSearches();
        $this->displayTopSearches($topSearches, $format);
        
        // Notícias sem imagem
        $newsWithoutImage = $this->getNewsWithoutImage();
        $this->displayNewsWithoutImage($newsWithoutImage, $format);
        
        $this->info('✅ Analytics gerados com sucesso!');
    }
    
    private function getGeneralStats()
    {
        return [
            'total_news' => News::count(),
            'news_with_images' => News::whereNotNull('url_to_image')->count(),
            'news_without_images' => News::whereNull('url_to_image')->count(),
            'total_searches' => SearchHistory::count(),
            'unique_sources' => News::distinct('source_name')->count(),
            'categories_covered' => News::distinct('category')->count(),
            'latest_news_date' => News::max('published_at'),
            'oldest_news_date' => News::min('published_at'),
        ];
    }
    
    private function getTopCategories()
    {
        return News::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }
    
    private function getTopSources()
    {
        return News::select('source_name', DB::raw('count(*) as count'))
            ->groupBy('source_name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }
    
    private function getTopSearches()
    {
        return SearchHistory::select('title', DB::raw('count(*) as count'))
            ->groupBy('title')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }
    
    private function getNewsWithoutImage()
    {
        return News::whereNull('url_to_image')
            ->select('id', 'title', 'category', 'source_name')
            ->limit(10)
            ->get();
    }
    
    private function displayStats($stats, $format)
    {
        $this->info("\n📈 Estatísticas Gerais:");
        
        if ($format === 'json') {
            $this->line(json_encode($stats, JSON_PRETTY_PRINT));
        } elseif ($format === 'csv') {
            $this->line('metric,value');
            foreach ($stats as $key => $value) {
                $this->line("$key,$value");
            }
        } else {
            $headers = ['Métrica', 'Valor'];
            $rows = [];
            foreach ($stats as $key => $value) {
                $rows[] = [$key, $value];
            }
            $this->table($headers, $rows);
        }
    }
    
    private function displayTopCategories($categories, $format)
    {
        $this->info("\n🏷️ Top Categorias:");
        
        if ($format === 'json') {
            $this->line(json_encode($categories, JSON_PRETTY_PRINT));
        } elseif ($format === 'csv') {
            $this->line('category,count');
            foreach ($categories as $category) {
                $this->line("{$category->category},{$category->count}");
            }
        } else {
            $headers = ['Categoria', 'Quantidade'];
            $rows = $categories->map(function ($category) {
                return [$category->category, $category->count];
            })->toArray();
            $this->table($headers, $rows);
        }
    }
    
    private function displayTopSources($sources, $format)
    {
        $this->info("\n📰 Top Fontes:");
        
        if ($format === 'json') {
            $this->line(json_encode($sources, JSON_PRETTY_PRINT));
        } elseif ($format === 'csv') {
            $this->line('source,count');
            foreach ($sources as $source) {
                $this->line("{$source->source_name},{$source->count}");
            }
        } else {
            $headers = ['Fonte', 'Quantidade'];
            $rows = $sources->map(function ($source) {
                return [$source->source_name, $source->count];
            })->toArray();
            $this->table($headers, $rows);
        }
    }
    
    private function displayTopSearches($searches, $format)
    {
        $this->info("\n🔍 Top Buscas:");
        
        if ($format === 'json') {
            $this->line(json_encode($searches, JSON_PRETTY_PRINT));
        } elseif ($format === 'csv') {
            $this->line('search_term,count');
            foreach ($searches as $search) {
                $this->line("{$search->title},{$search->count}");
            }
        } else {
            $headers = ['Termo de Busca', 'Quantidade'];
            $rows = $searches->map(function ($search) {
                return [$search->title, $search->count];
            })->toArray();
            $this->table($headers, $rows);
        }
    }
    
    private function displayNewsWithoutImage($news, $format)
    {
        $this->info("\n🖼️ Notícias Sem Imagem (Top 10):");
        
        if ($format === 'json') {
            $this->line(json_encode($news, JSON_PRETTY_PRINT));
        } elseif ($format === 'csv') {
            $this->line('id,title,category,source');
            foreach ($news as $item) {
                $this->line("{$item->id},{$item->title},{$item->category},{$item->source_name}");
            }
        } else {
            $headers = ['ID', 'Título', 'Categoria', 'Fonte'];
            $rows = $news->map(function ($item) {
                return [$item->id, $item->title, $item->category, $item->source_name];
            })->toArray();
            $this->table($headers, $rows);
        }
    }
}
