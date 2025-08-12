-- =====================================================
-- LIMPEZA DE MARCADORES [+char] - APLICAÇÃO LARAVEL
-- =====================================================

-- 1. ANÁLISE INICIAL
-- =====================================================

-- Contar notícias com [+char] em diferentes campos
SELECT 
    'Conteúdo' as campo,
    COUNT(*) as quantidade
FROM news 
WHERE content LIKE '%[+char]%'
UNION ALL
SELECT 
    'Título' as campo,
    COUNT(*) as quantidade
FROM news 
WHERE title LIKE '%[+char]%'
UNION ALL
SELECT 
    'Descrição' as campo,
    COUNT(*) as quantidade
FROM news 
WHERE description LIKE '%[+char]%';

-- 2. DETALHAMENTO DAS NOTÍCIAS COM [+char]
-- =====================================================

-- Notícias com [+char] no conteúdo (primeiras 10)
SELECT 
    id,
    title,
    source_name,
    LENGTH(content) as tamanho_conteudo,
    SUBSTRING(content, 1, 200) as inicio_conteudo
FROM news 
WHERE content LIKE '%[+char]%'
ORDER BY id
LIMIT 10;

-- Notícias com [+char] no título
SELECT 
    id,
    title,
    source_name
FROM news 
WHERE title LIKE '%[+char]%'
ORDER BY id;

-- Notícias com [+char] na descrição
SELECT 
    id,
    title,
    source_name,
    SUBSTRING(description, 1, 100) as inicio_descricao
FROM news 
WHERE description LIKE '%[+char]%'
ORDER BY id;

-- 3. ANÁLISE POR FONTE
-- =====================================================

-- Distribuição de marcadores [+char] por fonte
SELECT 
    source_name,
    COUNT(*) as quantidade_com_char,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM news WHERE source_name = n.source_name), 2) as percentual
FROM news n
WHERE content LIKE '%[+char]%'
GROUP BY source_name
ORDER BY quantidade_com_char DESC;

-- 4. ANÁLISE DE MÚLTIPLAS OCORRÊNCIAS
-- =====================================================

-- Notícias com múltiplas ocorrências de [+char] no conteúdo
SELECT 
    id,
    title,
    source_name,
    (LENGTH(content) - LENGTH(REPLACE(content, '[+char]', ''))) / LENGTH('[+char]') as ocorrencias_char
FROM news 
WHERE content LIKE '%[+char]%'
HAVING ocorrencias_char > 1
ORDER BY ocorrencias_char DESC;

-- 5. EXEMPLOS DE CONTEÚDO COM [+char]
-- =====================================================

-- Mostrar contexto ao redor dos marcadores [+char]
SELECT 
    id,
    title,
    SUBSTRING(
        content, 
        GREATEST(1, LOCATE('[+char]', content) - 50),
        100
    ) as contexto_char
FROM news 
WHERE content LIKE '%[+char]%'
ORDER BY id
LIMIT 10;

-- 6. CONSULTAS DE LIMPEZA (EXECUTAR COM CUIDADO)
-- =====================================================

-- IMPORTANTE: Faça backup antes de executar estas consultas!

-- Limpar [+char] do conteúdo
-- UPDATE news 
-- SET content = REPLACE(content, '[+char]', '')
-- WHERE content LIKE '%[+char]%';

-- Limpar [+char] do título
-- UPDATE news 
-- SET title = REPLACE(title, '[+char]', '')
-- WHERE title LIKE '%[+char]%';

-- Limpar [+char] da descrição
-- UPDATE news 
-- SET description = REPLACE(description, '[+char]', '')
-- WHERE description LIKE '%[+char]%';

-- 7. VERIFICAÇÃO PÓS-LIMPEZA
-- =====================================================

-- Verificar se ainda há marcadores [+char] após a limpeza
SELECT 
    'Conteúdo' as campo,
    COUNT(*) as quantidade_restante
FROM news 
WHERE content LIKE '%[+char]%'
UNION ALL
SELECT 
    'Título' as campo,
    COUNT(*) as quantidade_restante
FROM news 
WHERE title LIKE '%[+char]%'
UNION ALL
SELECT 
    'Descrição' as campo,
    COUNT(*) as quantidade_restante
FROM news 
WHERE description LIKE '%[+char]%';

-- 8. RELATÓRIO DE LIMPEZA
-- =====================================================

-- Relatório detalhado de limpeza
SELECT 
    'Antes da limpeza' as periodo,
    (SELECT COUNT(*) FROM news WHERE content LIKE '%[+char]%') as conteudo_com_char,
    (SELECT COUNT(*) FROM news WHERE title LIKE '%[+char]%') as titulo_com_char,
    (SELECT COUNT(*) FROM news WHERE description LIKE '%[+char]%') as descricao_com_char
UNION ALL
SELECT 
    'Após limpeza' as periodo,
    (SELECT COUNT(*) FROM news WHERE content LIKE '%[+char]%') as conteudo_com_char,
    (SELECT COUNT(*) FROM news WHERE title LIKE '%[+char]%') as titulo_com_char,
    (SELECT COUNT(*) FROM news WHERE description LIKE '%[+char]%') as descricao_com_char;

-- 9. CONSULTAS DE SEGURANÇA
-- =====================================================

-- Verificar se há outros marcadores similares
SELECT 
    'Marcadores similares encontrados' as tipo,
    COUNT(*) as quantidade
FROM news 
WHERE content LIKE '%[+%' OR content LIKE '%+]%'
UNION ALL
SELECT 
    'Marcadores similares no título' as tipo,
    COUNT(*) as quantidade
FROM news 
WHERE title LIKE '%[+%' OR title LIKE '%+]%'
UNION ALL
SELECT 
    'Marcadores similares na descrição' as tipo,
    COUNT(*) as quantidade
FROM news 
WHERE description LIKE '%[+%' OR description LIKE '%+]%';

-- 10. INSTRUÇÕES DE USO
-- =====================================================

/*
INSTRUÇÕES PARA LIMPEZA SEGURA:

1. FAÇA BACKUP DO BANCO ANTES DE EXECUTAR AS LIMPEZAS
2. Execute primeiro as consultas de análise (1-5)
3. Revise os resultados e confirme que são os marcadores corretos
4. Execute as consultas de limpeza (6) UMA POR VEZ
5. Verifique os resultados com as consultas de verificação (7-8)

COMANDOS LARAVEL ALTERNATIVOS:
- php artisan clean:char-markers --dry-run (para simulação)
- php artisan clean:char-markers (para limpeza real)
- php artisan clean:char-markers --field=content (apenas conteúdo)
- php artisan clean:char-markers --field=title (apenas título)
- php artisan clean:char-markers --field=description (apenas descrição)
*/
