# Análise de Dados - Aplicação Laravel de Notícias

Este conjunto de ferramentas permite analisar os dados da sua aplicação Laravel de notícias de diferentes formas.

## 📁 Arquivos Criados

1. **`analise_dados.sql`** - Consultas SQL prontas para execução
2. **`analise_dados.php`** - Script PHP para execução automática das análises
3. **`comandos_analise.md`** - Lista de comandos Artisan disponíveis
4. **`limpeza_char_markers.sql`** - Script específico para limpeza de marcadores [+char]
5. **`README_ANALISE_DADOS.md`** - Este arquivo de documentação

## 🚀 Como Usar

### Opção 1: Consultas SQL Diretas

Execute as consultas do arquivo `analise_dados.sql` diretamente no seu banco de dados:

```bash
# MySQL
mysql -u usuario -p nome_do_banco < analise_dados.sql

# PostgreSQL
psql -U usuario -d nome_do_banco -f analise_dados.sql

# SQLite
sqlite3 database.sqlite < analise_dados.sql
```

### Opção 2: Script PHP Automático

Execute o script PHP que formata os resultados de forma legível:

```bash
# Executar todas as análises
php analise_dados.php

# Executar análise específica
php analise_dados.php estatisticasGerais
php analise_dados.php noticiasPorCategoria
php analise_dados.php qualidadeDados
```

### Opção 3: Comandos Laravel Artisan

Use os comandos Artisan já disponíveis na sua aplicação:

```bash
# Verificar estado do conteúdo
php artisan check:content-format
php artisan find:empty-content
php artisan find:small-content

# Limpar problemas
php artisan clean:truncated-content
php artisan update:empty-content

# Limpar marcadores [+char]
php artisan clean:char-markers --dry-run  # Simulação
php artisan clean:char-markers            # Limpeza real

# Buscar novas notícias
php artisan fetch:daily-news
```

## 📊 Tipos de Análise Disponíveis

### 1. Estatísticas Gerais
- Total de notícias, usuários, buscas e histórico
- Visão geral da aplicação

### 2. Análise de Notícias
- **Por categoria**: Distribuição de notícias por categoria
- **Por fonte**: Top fontes de notícias
- **Por autor**: Autores mais frequentes
- **Por período**: Notícias por data de publicação

### 3. Análise de Buscas
- Termos mais buscados
- Padrões de busca por período
- Atividade de busca por hora do dia

### 4. Análise de Qualidade
- Notícias sem conteúdo/descrição
- Distribuição de tamanho de conteúdo
- Notícias com/sem imagens e URLs
- Identificação de duplicatas
- **Marcadores [+char] no conteúdo**

### 5. Análise Temporal
- Notícias por mês/ano
- Atividade recente (24h)
- Padrões de uso ao longo do tempo

## 🔧 Métodos Disponíveis no Script PHP

```bash
php analise_dados.php [metodo]
```

**Métodos disponíveis:**
- `estatisticasGerais` - Resumo geral da aplicação
- `noticiasPorCategoria` - Análise por categoria
- `noticiasPorFonte` - Top fontes de notícias
- `qualidadeDados` - Análise de qualidade
- `termosMaisBuscados` - Termos mais buscados
- `analiseTemporal` - Análise temporal
- `distribuicaoTamanhoConteudo` - Distribuição de tamanho
- `noticiasDuplicadas` - Identificar duplicatas
- `atividadeRecente` - Atividade das últimas 24h
- `executarTodasAnalises` - Executa todas as análises

## 📋 Exemplo de Saída

```
********************************************************************************
RELATÓRIO DE ESTATÍSTICAS GERAIS
********************************************************************************

============================================================
Estatísticas Gerais da Aplicação
============================================================
total_noticias | total_usuarios | total_buscas | total_historico
------------------------------------------------------------
1250 | 45 | 320 | 180

============================================================
Notícias por Categoria
============================================================
category | quantidade | percentual
----------------------------------------
technology | 450 | 36.00
business | 300 | 24.00
sports | 250 | 20.00
entertainment | 150 | 12.00
health | 100 | 8.00
```

## 🛠️ Comandos Artisan Úteis

### Verificação e Diagnóstico
```bash
# Verificar formato do conteúdo
php artisan check:content-format

# Encontrar conteúdo vazio
php artisan find:empty-content

# Encontrar conteúdo pequeno
php artisan find:small-content

# Verificar conteúdo truncado
php artisan check:truncated-content
```

### Limpeza e Correção
```bash
# Limpar conteúdo truncado
php artisan clean:truncated-content

# Atualizar conteúdo vazio
php artisan update:empty-content

# Atualizar conteúdo pequeno
php artisan update:small-content

# Remover marcadores de truncamento
php artisan remove:truncated-markers

# Limpar marcadores [+char]
php artisan clean:char-markers --dry-run  # Simulação primeiro
php artisan clean:char-markers            # Limpeza real
```

### Busca e Atualização
```bash
# Buscar notícias diárias
php artisan fetch:daily-news

# Buscar notícias completas
php artisan fetch:complete-news

# Atualizar todo o conteúdo
php artisan update:all-news-content
```

### Testes
```bash
# Testar API
php artisan test:api-direct

# Testar busca de notícias
php artisan test:news-fetch

# Testar formatação
php artisan test:content-formatting
```

## 📈 Dicas de Uso

1. **Execute verificações primeiro**: Use comandos de verificação antes de limpeza
2. **Monitore logs**: Verifique `storage/logs/` para detalhes de execução
3. **Backup antes de limpeza**: Faça backup antes de executar comandos de limpeza
4. **Análise regular**: Execute análises periodicamente para monitorar a qualidade
5. **Personalize consultas**: Modifique as consultas SQL conforme suas necessidades

## 🔍 Consultas Personalizadas

Você pode criar suas próprias consultas SQL baseadas nas tabelas:

- `news` - Notícias
- `users` - Usuários
- `searches` - Buscas realizadas
- `search_histories` - Histórico de buscas

## 📞 Suporte

Para dúvidas ou problemas:
1. Verifique os logs em `storage/logs/`
2. Execute comandos com `--help` para ver opções
3. Teste consultas SQL diretamente no banco antes de usar no script

---

**Desenvolvido para análise de dados da aplicação Laravel de Notícias**
