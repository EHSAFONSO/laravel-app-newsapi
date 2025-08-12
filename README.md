<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# 📰 Portal de Notícias - Laravel + Vue.js + Inertia.js

Um portal de notícias moderno e responsivo construído com Laravel, Vue.js e Inertia.js, integrado com a NewsAPI para fornecer notícias em tempo real.

## 🚀 Funcionalidades

- **📰 Destaques**: Notícias principais em destaque
- **🔍 Busca Avançada**: Busca por palavras-chave com resultados em tempo real
- **📂 Categorias**: Notícias organizadas por categoria (Geral, Negócios, Tecnologia, Esportes, etc.)
- **📚 Histórico**: Registro de todas as buscas realizadas
- **📱 Design Responsivo**: Interface adaptável para desktop, tablet e mobile
- **⚡ Performance**: Cache inteligente e otimizações de performance
- **🌍 API Real**: Integração com NewsAPI para notícias reais
- **🎨 Design Moderno**: Interface inspirada no Google News com placeholders estéticos
- **⚠️ Sistema de Fallback**: Dados de exemplo quando a API está indisponível
- **📊 Monitoramento**: Logs detalhados e alertas visuais
- **⏰ Agendamento Automático**: Busca de notícias uma vez por dia automaticamente

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 11
- **Frontend**: Vue.js 3 + Inertia.js
- **CSS**: Tailwind CSS 3.4.0
- **API**: NewsAPI
- **Banco de Dados**: SQL Server
- **Build Tool**: Vite
- **Cache**: Laravel Cache

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- **PHP 8.2+**
- **Composer**
- **Node.js 18+**
- **NPM ou Yarn**
- **SQL Server** (com drivers PHP SQL Server instalados)

## 🚀 Instalação

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd laravel-app
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Instale as dependências do Node.js

```bash
npm install
```

### 4. Configure o ambiente

Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

### 5. Configure o banco de dados SQL Server

Edite o arquivo `.env` e configure sua conexão com o SQL Server:

```env
DB_CONNECTION=sqlsrv
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=laraveldb
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

**Nota**: Certifique-se de que os drivers PHP SQL Server estão instalados e configurados.

### 6. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 7. Execute as migrações

```bash
php artisan migrate
```

### 8. Configure a NewsAPI (Opcional)

Para usar notícias reais, obtenha uma chave gratuita em [NewsAPI](https://newsapi.org/) e adicione ao `.env`:

```env
NEWS_API_KEY=sua_chave_aqui
```

**Nota**: Se não configurar a API key ou se a API estiver com limite excedido, o sistema usará dados de exemplo automaticamente.

### 9. Compile os assets

```bash
npm run build
```

### 10. Inicie o servidor

```bash
php artisan serve
```

A aplicação estará disponível em: `http://127.0.0.1:8000`

## ⏰ Configuração do Agendamento de Notícias

O sistema está configurado para buscar notícias automaticamente uma vez por dia às 8:00 da manhã.

### Comandos Disponíveis

```bash
# Testar a API de notícias
php artisan news:test-fetch

# Executar busca manual de notícias
php artisan news:fetch-daily

# Listar notícias salvas no banco
php artisan news:list --limit=10

# Verificar tarefas agendadas
php artisan schedule:list
```

### Configuração Automática (Windows)

Execute o script de configuração:

```bash
setup-windows-scheduler.bat
```

### Configuração Manual (Windows)

1. Abra o "Agendador de Tarefas" do Windows
2. Clique em "Criar Tarefa Básica"
3. Configure:
   - **Nome**: Laravel News Scheduler
   - **Gatilho**: Diariamente às 8:00
   - **Ação**: Iniciar um programa
   - **Programa**: `php`
   - **Argumentos**: `artisan schedule:run`
   - **Iniciar em**: `C:\caminho\para\laravel-app`

### Configuração (Linux/Mac)

Adicione ao crontab:

```bash
crontab -e

# Adicione esta linha:
* * * * * cd /caminho/para/laravel-app && php artisan schedule:run >> /dev/null 2>&1
```

### O que o Agendamento Faz

1. **Busca notícias em destaque** do Brasil (20 artigos)
2. **Busca por categorias**: tecnologia, negócios, esportes, entretenimento (10 artigos cada)
3. **Salva no banco de dados** na tabela `news`
4. **Evita duplicatas** verificando títulos existentes
5. **Registra logs** de todas as operações

### Personalização

Para alterar o horário, edite `app/Console/Kernel.php`:

```php
$schedule->command('news:fetch-daily')
        ->dailyAt('06:00') // Alterar para 6:00
        ->withoutOverlapping()
        ->runInBackground();
```

Para mais detalhes, consulte o arquivo `SCHEDULER_README.md`.

## 📁 Estrutura do Projeto

```
laravel-app/
├── app/
│   ├── Http/Controllers/
│   │   ├── NewsController.php      # Controlador principal de notícias
│   │   └── HistoryController.php   # Controlador do histórico
│   ├── Models/
│   │   ├── News.php               # Modelo de notícias
│   │   ├── SearchHistory.php      # Modelo do histórico
│   │   └── User.php               # Modelo de usuários
│   └── Services/
│       └── NewsApiService.php     # Serviço de integração com NewsAPI
├── resources/
│   └── js/
│       └── pages/
│           ├── news/
│           │   ├── index.vue      # Página principal
│           │   ├── search.vue     # Resultados de busca
│           │   ├── category.vue   # Notícias por categoria
│           │   └── show.vue       # Detalhes da notícia
│           ├── history.vue        # Página do histórico
│           └── welcome.vue        # Página inicial
├── routes/
│   └── web.php                    # Rotas da aplicação
└── database/
    └── migrations/                # Migrações do banco de dados
```

## 🎯 Como Usar

### Página Inicial (`/`)
- Landing page com design moderno
- Seção de notícias em destaque
- Links para categorias principais
- Informações sobre o projeto

### Página Principal (`/news`)
- Visualize notícias em destaque
- Navegue pelas categorias
- Use o formulário de busca
- Alerta visual quando API está com limite excedido

### Busca (`/news/search`)
- Digite palavras-chave para buscar notícias
- Visualize resultados paginados
- Acesse notícias completas

### Categorias (`/news/category/{categoria}`)
- Explore notícias por categoria:
  - Geral
  - Negócios
  - Tecnologia
  - Esportes
  - Entretenimento
  - Saúde
  - Ciência

### Histórico (`/history`)
- Visualize todas as buscas realizadas
- Repita buscas anteriores
- Faça novas buscas diretamente

## 🔧 Configurações Avançadas

### Cache
O sistema usa cache de 5 minutos para otimizar as requisições à API:

```bash
php artisan cache:clear  # Limpar cache
```

### Logs
Monitore as requisições à API nos logs:

```bash
# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 50

# Linux/Mac
tail -f storage/logs/laravel.log
```

### Sistema de Fallback Inteligente
O sistema possui fallback em múltiplas camadas:

1. **Tenta notícias do Brasil** (português)
2. **Se não encontrar, tenta EUA** (inglês)
3. **Se API falhar, usa dados de exemplo** com alerta visual
4. **Detecção específica de limite excedido** (erro 429)

### Placeholders Estéticos
Quando imagens não carregam, o sistema exibe:
- Gradientes coloridos por categoria
- Texto da categoria detectada automaticamente
- Design consistente com o layout

### SQL Server
Para verificar os dados no SQL Server:

```sql
-- Verificar histórico de buscas
SELECT * FROM search_histories ORDER BY created_at DESC;

-- Verificar usuários
SELECT * FROM users;

-- Verificar cache
SELECT * FROM cache;
```

## 🐛 Solução de Problemas

### Erro: "Page not found"
```bash
npm run build
php artisan cache:clear
```

### Erro: "Database connection failed"
- Verifique se o SQL Server está rodando
- Confirme se os drivers PHP SQL Server estão instalados
- Verifique as configurações no `.env`

### Erro: "SQL Server drivers not found"
Instale os drivers PHP SQL Server:
```bash
# Para Windows
# Baixe e instale o Microsoft Drivers for PHP for SQL Server
# Adicione a extensão no php.ini
```

### Erro: "API key invalid" ou "Rate Limited"
- **Limite excedido**: Aguarde algumas horas para reset (conta gratuita: 100 req/24h)
- **Chave inválida**: Verifique se a chave da NewsAPI está correta
- **Sistema funcionará** com dados de exemplo se a API falhar

### Tela em branco
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
npm run build
```

### Assets não carregam
```bash
# Inicie o Vite em modo de desenvolvimento
npm run dev

# Em outro terminal, inicie o Laravel
php artisan serve
```

## 📊 Comandos Úteis

```bash
# Limpar todos os caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recompilar assets
npm run build

# Modo de desenvolvimento (Hot Module Replacement)
npm run dev

# Verificar status das migrações
php artisan migrate:status

# Executar testes
php artisan test

# Acessar Tinker
php artisan tinker

# Verificar conexão com SQL Server
php artisan tinker --execute="echo 'DB: ' . config('database.default');"

# Comandos de Notícias
php artisan news:test-fetch      # Testar API de notícias
php artisan news:fetch-daily     # Executar busca manual
php artisan news:list --limit=5  # Listar notícias salvas
php artisan schedule:list        # Verificar tarefas agendadas
```

## 🌐 Endpoints da API

### NewsAPI
- **Destaques**: `/v2/top-headlines`
- **Busca**: `/v2/everything`
- **Categorias**: `/v2/top-headlines?category={categoria}`

### Rotas da Aplicação
- `GET /` - Página inicial
- `GET /news` - Página principal
- `POST /news/search` - Busca de notícias
- `GET /news/category/{category}` - Notícias por categoria
- `GET /history` - Histórico de buscas

## 🗄️ Banco de Dados

### SQL Server
A aplicação utiliza SQL Server como banco de dados principal:

- **Conexão**: `sqlsrv`
- **Tabelas principais**:
  - `users` - Usuários do sistema
  - `search_histories` - Histórico de buscas
  - `searches` - Buscas realizadas
  - `cache` - Cache da aplicação
  - `jobs` - Filas de trabalho
  - `news` - Notícias (se usar dados locais)

### Configuração
```env
DB_CONNECTION=sqlsrv
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=laraveldb
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

## ⚠️ Status da API

### NewsAPI - Conta Gratuita
- **Limite**: 100 requisições por 24 horas
- **Reset**: A cada 12 horas
- **Fallback**: Dados de exemplo quando limite excedido

### Alertas Visuais
O sistema exibe alertas quando:
- API está com limite excedido
- Dados de exemplo estão sendo usados
- Erros de conexão ocorrem

### Monitoramento
- Logs detalhados em `storage/logs/laravel.log`
- Status da API em tempo real
- Métricas de uso

## 🎨 Design e UX

### Interface Moderna
- Design inspirado no Google News
- Placeholders estéticos para imagens
- Gradientes coloridos por categoria
- Responsivo para todos os dispositivos

### Funcionalidades
- Busca em tempo real
- Navegação intuitiva
- Histórico de buscas
- Paginação otimizada

## 🤝 Contribuindo

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 📞 Suporte

Se você encontrar algum problema ou tiver dúvidas:

1. Verifique os logs em `storage/logs/laravel.log`
2. Consulte a documentação do Laravel e Vue.js
3. Abra uma issue no repositório

## 🎉 Agradecimentos

- [Laravel](https://laravel.com/) - Framework PHP
- [Vue.js](https://vuejs.org/) - Framework JavaScript
- [Inertia.js](https://inertiajs.com/) - Adapter para SPA
- [Tailwind CSS](https://tailwindcss.com/) - Framework CSS
- [NewsAPI](https://newsapi.org/) - API de notícias
- [Microsoft SQL Server](https://www.microsoft.com/en-us/sql-server/) - Banco de dados

---

**Desenvolvido com ❤️ por Eduardo Henrique Dos Santos Afonso**

**LinkedIn**: [https://www.linkedin.com/in/ehsafonso/](https://www.linkedin.com/in/ehsafonso/)

**Portal de Notícias** - Laravel + Vue.js + Inertia.js + SQL Server
