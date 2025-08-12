<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\News;
use App\Models\NewsImage;
use App\Models\SearchHistory;

class AnalyzeDatabasePerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:analyze-performance {--detailed : Mostrar análise detalhada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analisa a performance atual do banco de dados e sugere melhorias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Analisando Performance do Banco de Dados...');
        $this->newLine();

        // 1. Análise de Tabelas
        $this->analyzeTables();
        
        // 2. Análise de Índices
        $this->analyzeIndexes();
        
        // 3. Análise de Consultas
        $this->analyzeQueries();
        
        // 4. Análise de Dados
        $this->analyzeData();
        
        // 5. Sugestões de Melhoria
        $this->suggestImprovements();
        
        $this->newLine();
        $this->info('✅ Análise concluída!');
    }

    private function analyzeTables()
    {
        $this->info('📊 Análise de Tabelas:');
        
        $tables = ['news', 'news_images', 'searches', 'search_histories', 'users'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $size = $this->getTableSize($table);
                
                $this->line("  • {$table}: {$count} registros, {$size}");
            }
        }
        
        $this->newLine();
    }

    private function analyzeIndexes()
    {
        $this->info('🔍 Análise de Índices:');
        
        $tables = ['news', 'news_images', 'searches', 'search_histories'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $indexes = $this->getTableIndexes($table);
                
                $this->line("  • {$table}:");
                foreach ($indexes as $index) {
                    $this->line("    - {$index}");
                }
            }
        }
        
        $this->newLine();
    }

    private function analyzeQueries()
    {
        $this->info('⚡ Análise de Consultas:');
        
        // Teste de performance de consultas comuns
        $queries = [
            'Notícias por categoria' => function() {
                return News::where('category', 'technology')
                    ->orderBy('published_at', 'desc')
                    ->take(10)
                    ->get();
            },
            'Notícias recentes' => function() {
                return News::orderBy('published_at', 'desc')
                    ->take(20)
                    ->get();
            },
            'Busca por título' => function() {
                return News::where('title', 'like', '%tecnologia%')
                    ->get();
            },
            'Imagens de notícias' => function() {
                return NewsImage::with('news')
                    ->take(10)
                    ->get();
            }
        ];
        
        foreach ($queries as $name => $query) {
            $start = microtime(true);
            $result = $query();
            $time = (microtime(true) - $start) * 1000;
            
            $this->line("  • {$name}: {$time}ms ({$result->count()} resultados)");
        }
        
        $this->newLine();
    }

    private function analyzeData()
    {
        $this->info('📈 Análise de Dados:');
        
        // Estatísticas das notícias
        $totalNews = News::count();
        $newsWithImages = News::whereNotNull('url_to_image')->count();
        $newsWithContent = News::whereNotNull('content')->count();
        $newsWithAuthor = News::whereNotNull('author')->count();
        
        $this->line("  • Total de notícias: {$totalNews}");
        $this->line("  • Com imagens: {$newsWithImages} (" . round(($newsWithImages/$totalNews)*100, 1) . "%)");
        $this->line("  • Com conteúdo: {$newsWithContent} (" . round(($newsWithContent/$totalNews)*100, 1) . "%)");
        $this->line("  • Com autor: {$newsWithAuthor} (" . round(($newsWithAuthor/$totalNews)*100, 1) . "%)");
        
        // Categorias mais populares
        $categories = News::selectRaw('category, COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
        
        $this->line("  • Top categorias:");
        foreach ($categories as $cat) {
            $this->line("    - {$cat->category}: {$cat->count} notícias");
        }
        
        // Fontes mais frequentes
        $sources = News::selectRaw('source_name, COUNT(*) as count')
            ->whereNotNull('source_name')
            ->groupBy('source_name')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
        
        $this->line("  • Top fontes:");
        foreach ($sources as $source) {
            $this->line("    - {$source->source_name}: {$source->count} notícias");
        }
        
        $this->newLine();
    }

    private function suggestImprovements()
    {
        $this->info('🚀 Sugestões de Melhoria:');
        
        $suggestions = [
            'Índices' => [
                'Adicionar índice em news(published_at DESC) para consultas por data',
                'Adicionar índice em news(category, published_at DESC) para filtros por categoria',
                'Adicionar índice em news(source_name) para filtros por fonte',
                'Adicionar índice em news(title) para buscas por título'
            ],
            'Normalização' => [
                'Criar tabela sources para normalizar source_name',
                'Criar tabela categories para normalizar category',
                'Criar tabela authors para normalizar author'
            ],
            'Performance' => [
                'Implementar cache para consultas frequentes',
                'Adicionar paginação otimizada',
                'Implementar lazy loading para imagens'
            ],
            'Analytics' => [
                'Criar tabela news_views para tracking de visualizações',
                'Criar tabela news_shares para tracking de compartilhamentos',
                'Criar tabela news_bookmarks para favoritos'
            ],
            'SEO' => [
                'Adicionar campo slug para URLs amigáveis',
                'Adicionar meta_description e meta_keywords',
                'Calcular tempo de leitura automaticamente'
            ]
        ];
        
        foreach ($suggestions as $category => $items) {
            $this->line("  • {$category}:");
            foreach ($items as $item) {
                $this->line("    - {$item}");
            }
        }
        
        $this->newLine();
        
        if ($this->option('detailed')) {
            $this->showDetailedRecommendations();
        }
    }

    private function showDetailedRecommendations()
    {
        $this->info('📋 Recomendações Detalhadas:');
        
        $this->line('1. Criar migration para índices:');
        $this->line('   php artisan make:migration add_performance_indexes_to_news_table');
        
        $this->line('2. Criar tabelas normalizadas:');
        $this->line('   php artisan make:migration create_sources_table');
        $this->line('   php artisan make:migration create_categories_table');
        $this->line('   php artisan make:migration create_authors_table');
        
        $this->line('3. Criar tabelas de analytics:');
        $this->line('   php artisan make:migration create_news_views_table');
        $this->line('   php artisan make:migration create_news_shares_table');
        $this->line('   php artisan make:migration create_news_bookmarks_table');
        
        $this->newLine();
    }

    private function getTableSize($table)
    {
        try {
            $result = DB::select("
                SELECT 
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size_MB'
                FROM information_schema.tables 
                WHERE table_schema = DATABASE() 
                AND table_name = ?
            ", [$table]);
            
            return isset($result[0]) ? $result[0]->Size_MB . ' MB' : 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getTableIndexes($table)
    {
        try {
            $indexes = DB::select("
                SELECT INDEX_NAME, COLUMN_NAME
                FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ?
                AND INDEX_NAME != 'PRIMARY'
                ORDER BY INDEX_NAME, SEQ_IN_INDEX
            ", [$table]);
            
            $indexGroups = [];
            foreach ($indexes as $index) {
                if (!isset($indexGroups[$index->INDEX_NAME])) {
                    $indexGroups[$index->INDEX_NAME] = [];
                }
                $indexGroups[$index->INDEX_NAME][] = $index->COLUMN_NAME;
            }
            
            $result = [];
            foreach ($indexGroups as $name => $columns) {
                $result[] = $name . ' (' . implode(', ', $columns) . ')';
            }
            
            return $result;
        } catch (\Exception $e) {
            return ['Erro ao obter índices'];
        }
    }
}
