# Agendamento de Busca de Notícias

Este projeto foi configurado para buscar notícias da API automaticamente uma vez por dia.

## Configuração

### 1. Executar Migrações
Primeiro, execute as migrações para criar/atualizar a tabela de notícias:

```bash
php artisan migrate
```

### 2. Configurar o Cron Job (Linux/Mac)
Para que o agendamento funcione, você precisa configurar um cron job no servidor:

```bash
# Abra o crontab
crontab -e

# Adicione a seguinte linha (ajuste o caminho conforme necessário)
* * * * * cd /path/to/your/laravel-app && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Configurar o Task Scheduler (Windows)
Para Windows, você pode usar o Task Scheduler:

1. Abra o "Agendador de Tarefas" do Windows
2. Crie uma nova tarefa básica
3. Configure para executar diariamente às 8:00
4. Ação: Iniciar um programa
5. Programa: `php`
6. Argumentos: `artisan schedule:run`
7. Iniciar em: `C:\path\to\your\laravel-app`

## Comandos Disponíveis

### Testar a API
Para testar se a API está funcionando:

```bash
php artisan news:test-fetch
```

### Executar Busca Manual
Para executar a busca de notícias manualmente:

```bash
php artisan news:fetch-daily
```

### Verificar Agendamento
Para verificar quais tarefas estão agendadas:

```bash
php artisan schedule:list
```

## Como Funciona

1. **Agendamento**: O comando `news:fetch-daily` está configurado para executar diariamente às 8:00 da manhã
2. **Busca de Notícias**: O comando busca:
   - Notícias em destaque do Brasil (20 artigos)
   - Notícias por categoria: tecnologia, negócios, esportes, entretenimento (10 artigos cada)
3. **Armazenamento**: As notícias são salvas no banco de dados na tabela `news`
4. **Evitar Duplicatas**: O sistema verifica se a notícia já existe pelo título antes de salvar

## Logs

Os logs das execuções são salvos em:
- `storage/logs/laravel.log` - Logs gerais da aplicação
- Console - Durante execução manual dos comandos

## Personalização

### Alterar Horário
Para alterar o horário da execução, edite o arquivo `app/Console/Kernel.php`:

```php
// Exemplo: executar às 6:00 da manhã
$schedule->command('news:fetch-daily')
        ->dailyAt('06:00')
        ->withoutOverlapping()
        ->runInBackground();
```

### Alterar Categorias
Para alterar as categorias buscadas, edite o arquivo `app/Console/Commands/FetchDailyNews.php`:

```php
$categories = ['technology', 'business', 'sports', 'entertainment', 'health'];
```

### Alterar Quantidade de Notícias
Para alterar a quantidade de notícias buscadas:

```php
// Em FetchDailyNews.php
$headlines = $newsService->getTopHeadlines('br', 1, 30); // 30 notícias em destaque
$categoryNews = $newsService->getNewsByCategory($category, 'br', 1, 15); // 15 por categoria
```

## Troubleshooting

### Comando não executa
1. Verifique se o cron job está configurado corretamente
2. Teste manualmente: `php artisan news:fetch-daily`
3. Verifique os logs: `tail -f storage/logs/laravel.log`

### Erro de API
1. Verifique se a API key está configurada no `.env`
2. Teste a API: `php artisan news:test-fetch`
3. Verifique se não excedeu o limite de requisições

### Erro de Banco de Dados
1. Execute as migrações: `php artisan migrate`
2. Verifique se a tabela `news` existe e tem os campos corretos
3. Verifique as permissões do banco de dados
