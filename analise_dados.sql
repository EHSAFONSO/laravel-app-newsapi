-- =====================================================
-- ANÁLISE DE DADOS - APLICAÇÃO DE NOTÍCIAS LARAVEL
-- =====================================================

-- 1. ESTATÍSTICAS GERAIS
-- =====================================================

-- Total de notícias no sistema
SELECT COUNT(*) as total_noticias FROM news;

-- Total de usuários registrados
SELECT COUNT(*) as total_usuarios FROM users;

-- Total de buscas realizadas
SELECT COUNT(*) as total_buscas FROM searches;

-- Total de histórico de buscas
SELECT COUNT(*) as total_historico_buscas FROM search_histories;

-- 2. ANÁLISE DE NOTÍCIAS
-- =====================================================

-- Notícias por categoria
SELECT 
    category,
    COUNT(*) as quantidade,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM news), 2) as percentual
FROM news 
WHERE category IS NOT NULL
GROUP BY category 
ORDER BY quantidade DESC;

-- Notícias por fonte
SELECT 
    source_name,
    COUNT(*) as quantidade,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM news), 2) as percentual
FROM news 
WHERE source_name IS NOT NULL
GROUP BY source_name 
ORDER BY quantidade DESC
LIMIT 10;

-- Notícias por autor
SELECT 
    author,
    COUNT(*) as quantidade
FROM news 
WHERE author IS NOT NULL AND author != ''
GROUP BY author 
ORDER BY quantidade DESC
LIMIT 10;

-- Notícias por período (últimos 30 dias)
SELECT 
    DATE(published_at) as data,
    COUNT(*) as quantidade
FROM news 
WHERE published_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(published_at)
ORDER BY data DESC;

-- Notícias sem conteúdo
SELECT 
    COUNT(*) as noticias_sem_conteudo
FROM news 
WHERE content IS NULL OR content = '';

-- Notícias sem descrição
SELECT 
    COUNT(*) as noticias_sem_descricao
FROM news 
WHERE description IS NULL OR description = '';

-- 3. ANÁLISE DE BUSCAS
-- =====================================================

-- Termos mais buscados
SELECT 
    query,
    COUNT(*) as quantidade_buscas
FROM searches 
GROUP BY query 
ORDER BY quantidade_buscas DESC
LIMIT 10;

-- Buscas por período (últimos 7 dias)
SELECT 
    DATE(created_at) as data,
    COUNT(*) as quantidade_buscas
FROM searches 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY DATE(created_at)
ORDER BY data DESC;

-- 4. ANÁLISE DE HISTÓRICO DE BUSCAS
-- =====================================================

-- Histórico de buscas por usuário
SELECT 
    u.name as usuario,
    COUNT(sh.id) as quantidade_buscas
FROM search_histories sh
LEFT JOIN users u ON sh.user_id = u.id
GROUP BY sh.user_id, u.name
ORDER BY quantidade_buscas DESC;

-- Títulos mais acessados no histórico
SELECT 
    title,
    COUNT(*) as quantidade_acessos
FROM search_histories 
GROUP BY title 
ORDER BY quantidade_acessos DESC
LIMIT 10;

-- 5. ANÁLISE TEMPORAL
-- =====================================================

-- Notícias por mês/ano
SELECT 
    YEAR(published_at) as ano,
    MONTH(published_at) as mes,
    COUNT(*) as quantidade
FROM news 
WHERE published_at IS NOT NULL
GROUP BY YEAR(published_at), MONTH(published_at)
ORDER BY ano DESC, mes DESC;

-- Atividade de buscas por hora do dia
SELECT 
    HOUR(created_at) as hora,
    COUNT(*) as quantidade_buscas
FROM searches 
GROUP BY HOUR(created_at)
ORDER BY hora;

-- 6. ANÁLISE DE QUALIDADE DOS DADOS
-- =====================================================

-- Notícias com URLs de imagem
SELECT 
    COUNT(*) as noticias_com_imagem
FROM news 
WHERE url_to_image IS NOT NULL AND url_to_image != '';

-- Notícias com URLs válidas
SELECT 
    COUNT(*) as noticias_com_url
FROM news 
WHERE url IS NOT NULL AND url != '';

-- Distribuição de tamanho de conteúdo
SELECT 
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
ORDER BY quantidade DESC;

-- 7. ANÁLISE DE PERFORMANCE
-- =====================================================

-- Notícias mais recentes (últimas 24h)
SELECT 
    title,
    published_at,
    source_name
FROM news 
WHERE published_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
ORDER BY published_at DESC;

-- Notícias mais antigas
SELECT 
    title,
    published_at,
    source_name
FROM news 
WHERE published_at IS NOT NULL
ORDER BY published_at ASC
LIMIT 10;

-- 8. RELATÓRIO RESUMIDO
-- =====================================================

-- Resumo geral da aplicação
SELECT 
    (SELECT COUNT(*) FROM news) as total_noticias,
    (SELECT COUNT(*) FROM users) as total_usuarios,
    (SELECT COUNT(*) FROM searches) as total_buscas,
    (SELECT COUNT(*) FROM search_histories) as total_historico,
    (SELECT COUNT(*) FROM news WHERE published_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) as noticias_24h,
    (SELECT COUNT(*) FROM news WHERE content IS NOT NULL AND content != '') as noticias_com_conteudo,
    (SELECT COUNT(DISTINCT category) FROM news WHERE category IS NOT NULL) as categorias_unicas,
    (SELECT COUNT(DISTINCT source_name) FROM news WHERE source_name IS NOT NULL) as fontes_unicas;

-- 9. CONSULTAS ESPECÍFICAS PARA DEBUG
-- =====================================================

-- Notícias duplicadas por título
SELECT 
    title,
    COUNT(*) as quantidade
FROM news 
GROUP BY title 
HAVING COUNT(*) > 1
ORDER BY quantidade DESC;

-- Notícias com títulos muito longos
SELECT 
    title,
    LENGTH(title) as tamanho_titulo
FROM news 
WHERE LENGTH(title) > 200
ORDER BY LENGTH(title) DESC;

-- Notícias sem data de publicação
SELECT 
    id,
    title,
    source_name
FROM news 
WHERE published_at IS NULL
LIMIT 10;

-- 10. CONSULTAS PARA MONITORAMENTO
-- =====================================================

-- Atividade recente (última hora)
SELECT 
    'Notícias criadas' as tipo,
    COUNT(*) as quantidade
FROM news 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
UNION ALL
SELECT 
    'Buscas realizadas' as tipo,
    COUNT(*) as quantidade
FROM searches 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
UNION ALL
SELECT 
    'Usuários registrados' as tipo,
    COUNT(*) as quantidade
FROM users 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR);

-- 11. ANÁLISE ESPECÍFICA DE [+char]
-- =====================================================

-- Notícias que contêm [+char] no conteúdo
SELECT 
    id,
    title,
    source_name,
    LENGTH(content) as tamanho_conteudo,
    SUBSTRING(content, 1, 200) as inicio_conteudo
FROM news 
WHERE content LIKE '%[+char]%'
ORDER BY id;

-- Contagem de notícias com [+char]
SELECT 
    COUNT(*) as total_com_char_marker
FROM news 
WHERE content LIKE '%[+char]%';

-- Notícias que contêm [+char] no título
SELECT 
    id,
    title,
    source_name
FROM news 
WHERE title LIKE '%[+char]%'
ORDER BY id;

-- Notícias que contêm [+char] na descrição
SELECT 
    id,
    title,
    source_name,
    SUBSTRING(description, 1, 100) as inicio_descricao
FROM news 
WHERE description LIKE '%[+char]%'
ORDER BY id;

-- Distribuição de marcadores [+char] por fonte
SELECT 
    source_name,
    COUNT(*) as quantidade_com_char
FROM news 
WHERE content LIKE '%[+char]%'
GROUP BY source_name
ORDER BY quantidade_com_char DESC;

-- Notícias com múltiplas ocorrências de [+char]
SELECT 
    id,
    title,
    source_name,
    (LENGTH(content) - LENGTH(REPLACE(content, '[+char]', ''))) / LENGTH('[+char]') as ocorrencias_char
FROM news 
WHERE content LIKE '%[+char]%'
HAVING ocorrencias_char > 1
ORDER BY ocorrencias_char DESC;

-- 12. CONSULTAS PARA LIMPEZA
-- =====================================================

-- Exemplo de como limpar [+char] do conteúdo (NÃO EXECUTAR DIRETAMENTE)
-- UPDATE news 
-- SET content = REPLACE(content, '[+char]', '')
-- WHERE content LIKE '%[+char]%';

-- Exemplo de como limpar [+char] do título (NÃO EXECUTAR DIRETAMENTE)
-- UPDATE news 
-- SET title = REPLACE(title, '[+char]', '')
-- WHERE title LIKE '%[+char]%';

-- Exemplo de como limpar [+char] da descrição (NÃO EXECUTAR DIRETAMENTE)
-- UPDATE news 
-- SET description = REPLACE(description, '[+char]', '')
-- WHERE description LIKE '%[+char]%';
