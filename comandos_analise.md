# Comandos Laravel Artisan para Análise de Dados

## Comandos Disponíveis na Aplicação

### 1. Análise de Conteúdo
```bash
# Verificar formato do conteúdo
php artisan check:content-format

# Verificar conteúdo de notícias
php artisan check:news-content

# Verificar conteúdo truncado
php artisan check:truncated-content

# Limpar conteúdo truncado
php artisan clean:truncated-content

# Limpar descrições truncadas
php artisan clean:truncated-descriptions

# Limpeza manual de conteúdo truncado
php artisan manual:clean-truncated

# Remover marcadores de truncamento
php artisan remove:truncated-markers

# Limpar marcadores [+char]
php artisan clean:char-markers
```

### 2. Busca e Atualização de Notícias
```bash
# Buscar notícias completas
php artisan fetch:complete-news

# Buscar notícias diárias
php artisan fetch:daily-news

# Atualizar todo o conteúdo de notícias
php artisan update:all-news-content

# Atualizar conteúdo vazio
php artisan update:empty-content

# Atualizar conteúdo pequeno
php artisan update:small-content
```

### 3. Análise e Listagem
```bash
# Listar notícias
php artisan list:news

# Encontrar conteúdo vazio
php artisan find:empty-content

# Encontrar conteúdo pequeno
php artisan find:small-content

# Encontrar conteúdo não formatado
php artisan find:unformatted-content
```

### 4. Testes e Verificação
```bash
# Testar API diretamente
php artisan test:api-direct

# Testar limite da API
php artisan test:api-limit

# Testar busca de conteúdo
php artisan test:content-fetching

# Testar formatação de conteúdo
php artisan test:content-formatting

# Testar busca de notícias
php artisan test:news-fetch

# Testar rota de notícias
php artisan test:news-route

# Testar página de boas-vindas
php artisan test:welcome-page

# Simular rota de boas-vindas
php artisan simulate:welcome-route
```

### 5. Verificação de Status
```bash
# Verificar status de boas-vindas
php artisan check:welcome-status
```

## Como Usar

1. **Execute os comandos no terminal** dentro da pasta do projeto Laravel
2. **Para análise específica**, use os comandos de verificação primeiro
3. **Para correção automática**, use os comandos de limpeza
4. **Para testes**, use os comandos de teste para verificar funcionalidades

## Exemplos de Uso

```bash
# Verificar o estado atual do conteúdo
php artisan check:content-format
php artisan find:empty-content
php artisan find:small-content

# Limpar problemas encontrados
php artisan clean:truncated-content
php artisan update:empty-content

# Testar funcionalidades
php artisan test:api-direct
php artisan test:news-fetch

# Buscar novas notícias
php artisan fetch:daily-news
```

## Dicas

- Execute os comandos de verificação antes dos de correção
- Use `--help` após qualquer comando para ver opções específicas
- Monitore os logs em `storage/logs/` para detalhes de execução
- Alguns comandos podem demorar dependendo da quantidade de dados
