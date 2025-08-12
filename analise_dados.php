<?php

/**
 * Script de Análise de Dados - Aplicação Laravel de Notícias
 * 
 * Este script executa consultas SQL para analisar os dados da aplicação
 * e gera relatórios em formato legível.
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Carregar configuração do Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

class AnaliseDados
{
    private $pdo;
    
    public function __construct()
    {
        $this->pdo = DB::connection()->getPdo();
    }
    
    /**
     * Executa uma consulta SQL e retorna os resultados
     */
    private function executarConsulta($sql, $descricao = '')
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($descricao) {
                echo "\n" . str_repeat('=', 60) . "\n";
                echo $descricao . "\n";
                echo str_repeat('=', 60) . "\n";
            }
            
            if (empty($resultados)) {
                echo "Nenhum resultado encontrado.\n";
                return;
            }
            
            // Exibir cabeçalhos
            $headers = array_keys($resultados[0]);
            echo implode(' | ', $headers) . "\n";
            echo str_repeat('-', strlen(implode(' | ', $headers))) . "\n";
            
            // Exibir dados
            foreach ($resultados as $row) {
                echo implode(' | ', array_map(function($value) {
                    return is_null($value) ? 'NULL' : (string)$value;
                }, $row)) . "\n";
            }
            
        } catch (Exception $e) {
            echo "Erro na consulta: " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * Relatório de estatísticas gerais
     */
    public function estatisticasGerais()
    {
        echo "\n" . str_repeat('*', 80) . "\n";
        echo "RELATÓRIO DE ESTATÍSTICAS GERAIS\n";
        echo str_repeat('*', 80) . "\n";
        
        $this->executarConsulta(
            "SELECT 
                (SELECT COUNT(*) FROM news) as total_noticias,
                (SELECT COUNT(*) FROM users) as total_usuarios,
                (SELECT COUNT(*) FROM searches) as total_buscas,
                (SELECT COUNT(*) FROM search_histories) as total_historico",
            "Estatísticas Gerais da Aplicação"
        );
    }
    
    /**
     * Análise de notícias por categoria
     */
    public function noticiasPorCategoria()
    {
        $this->executarConsulta(
            "SELECT 
                category,
                COUNT(*) as quantidade,
                ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM news), 2) as percentual
            FROM news 
            WHERE category IS NOT NULL
            GROUP BY category 
            ORDER BY quantidade DESC",
            "Notícias por Categoria"
        );
    }
    
    /**
     * Análise de notícias por fonte
     */
    public function noticiasPorFonte()
    {
        $this->executarConsulta(
            "SELECT 
                source_name,
                COUNT(*) as quantidade,
                ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM news), 2) as percentual
            FROM news 
            WHERE source_name IS NOT NULL
            GROUP BY source_name 
            ORDER BY quantidade DESC
            LIMIT 10",
            "Top 10 Fontes de Notícias"
        );
    }
    
    /**
     * Análise de qualidade dos dados
     */
    public function qualidadeDados()
    {
        $this->executarConsulta(
            "SELECT 
                (SELECT COUNT(*) FROM news WHERE content IS NULL OR content = '') as noticias_sem_conteudo,
                (SELECT COUNT(*) FROM news WHERE description IS NULL OR description = '') as noticias_sem_descricao,
                (SELECT COUNT(*) FROM news WHERE url_to_image IS NOT NULL AND url_to_image != '') as noticias_com_imagem,
                (SELECT COUNT(*) FROM news WHERE url IS NOT NULL AND url != '') as noticias_com_url,
                (SELECT COUNT(*) FROM news WHERE published_at IS NULL) as noticias_sem_data",
            "Análise de Qualidade dos Dados"
        );
    }
    
    /**
     * Termos mais buscados
     */
    public function termosMaisBuscados()
    {
        $this->executarConsulta(
            "SELECT 
                query,
                COUNT(*) as quantidade_buscas
            FROM searches 
            GROUP BY query 
            ORDER BY quantidade_buscas DESC
            LIMIT 10",
            "Top 10 Termos Mais Buscados"
        );
    }
    
    /**
     * Análise temporal de notícias
     */
    public function analiseTemporal()
    {
        $this->executarConsulta(
            "SELECT 
                DATE(published_at) as data,
                COUNT(*) as quantidade
            FROM news 
            WHERE published_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(published_at)
            ORDER BY data DESC
            LIMIT 15",
            "Notícias dos Últimos 15 Dias"
        );
    }
    
    /**
     * Distribuição de tamanho de conteúdo
     */
    public function distribuicaoTamanhoConteudo()
    {
        $this->executarConsulta(
            "SELECT 
                CASE 
                    WHEN LENGTH(content) < 100 THEN 'Muito curto (< 100 chars)'
                    WHEN LENGTH(content) < 500 THEN 'Curto (100-500 chars)'
                    WHEN LENGTH(content) < 1000 THEN 'Médio (500-1000 chars)'
                    WHEN LENGTH(content) < 2000 THEN 'Longo (1000-2000 chars)'
                    ELSE 'Muito longo (> 2000 chars)'
                END as tamanho_conteudo,
                COUNT(*) as quantidade
            FROM news 
            WHERE content IS NOT NULL
            GROUP BY 
                CASE 
                    WHEN LENGTH(content) < 100 THEN 'Muito curto (< 100 chars)'
                    WHEN LENGTH(content) < 500 THEN 'Curto (100-500 chars)'
                    WHEN LENGTH(content) < 1000 THEN 'Médio (500-1000 chars)'
                    WHEN LENGTH(content) < 2000 THEN 'Longo (1000-2000 chars)'
                    ELSE 'Muito longo (> 2000 chars)'
                END
            ORDER BY quantidade DESC",
            "Distribuição de Tamanho de Conteúdo"
        );
    }
    
    /**
     * Notícias duplicadas
     */
    public function noticiasDuplicadas()
    {
        $this->executarConsulta(
            "SELECT 
                title,
                COUNT(*) as quantidade
            FROM news 
            GROUP BY title 
            HAVING COUNT(*) > 1
            ORDER BY quantidade DESC
            LIMIT 10",
            "Top 10 Notícias Duplicadas"
        );
    }
    
    /**
     * Atividade recente
     */
    public function atividadeRecente()
    {
        $this->executarConsulta(
            "SELECT 
                'Notícias criadas' as tipo,
                COUNT(*) as quantidade
            FROM news 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            UNION ALL
            SELECT 
                'Buscas realizadas' as tipo,
                COUNT(*) as quantidade
            FROM searches 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            UNION ALL
            SELECT 
                'Usuários registrados' as tipo,
                COUNT(*) as quantidade
            FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)",
            "Atividade das Últimas 24 Horas"
        );
    }
    
    /**
     * Executa todas as análises
     */
    public function executarTodasAnalises()
    {
        $this->estatisticasGerais();
        $this->noticiasPorCategoria();
        $this->noticiasPorFonte();
        $this->qualidadeDados();
        $this->termosMaisBuscados();
        $this->analiseTemporal();
        $this->distribuicaoTamanhoConteudo();
        $this->noticiasDuplicadas();
        $this->atividadeRecente();
        
        echo "\n" . str_repeat('*', 80) . "\n";
        echo "ANÁLISE CONCLUÍDA\n";
        echo str_repeat('*', 80) . "\n";
    }
}

// Executar análise
if (php_sapi_name() === 'cli') {
    $analise = new AnaliseDados();
    
    if (isset($argv[1])) {
        $metodo = $argv[1];
        if (method_exists($analise, $metodo)) {
            $analise->$metodo();
        } else {
            echo "Método '$metodo' não encontrado.\n";
            echo "Métodos disponíveis:\n";
            echo "- estatisticasGerais\n";
            echo "- noticiasPorCategoria\n";
            echo "- noticiasPorFonte\n";
            echo "- qualidadeDados\n";
            echo "- termosMaisBuscados\n";
            echo "- analiseTemporal\n";
            echo "- distribuicaoTamanhoConteudo\n";
            echo "- noticiasDuplicadas\n";
            echo "- atividadeRecente\n";
            echo "- executarTodasAnalises\n";
        }
    } else {
        $analise->executarTodasAnalises();
    }
} else {
    echo "Este script deve ser executado via linha de comando.\n";
    echo "Uso: php analise_dados.php [metodo]\n";
}
