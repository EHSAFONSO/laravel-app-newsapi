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

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 11
- **Frontend**: Vue.js 3 + Inertia.js
- **CSS**: Tailwind CSS
- **API**: NewsAPI
- **Banco de Dados**: MySQL/SQLite
- **Build Tool**: Vite

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- **PHP 8.2+**
- **Composer**
- **Node.js 18+**
- **NPM ou Yarn**
- **MySQL ou SQLite**

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

### 5. Configure o banco de dados

Edite o arquivo `.env` e configure sua conexão com o banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_noticias
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

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

**Nota**: Se não configurar a API key, o sistema usará dados de exemplo.

### 9. Compile os assets

```bash
npm run build
```

### 10. Inicie o servidor

```bash
php artisan serve
```

A aplicação estará disponível em: `http://127.0.0.1:8000`

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
│           └── history.vue        # Página do histórico
├── routes/
│   └── web.php                    # Rotas da aplicação
└── database/
    └── migrations/                # Migrações do banco de dados
```

## 🎯 Como Usar

### Página Principal (`/news`)
- Visualize notícias em destaque
- Navegue pelas categorias
- Use o formulário de busca

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
tail -f storage/logs/laravel.log
```

### Fallback da API
O sistema possui fallback inteligente:
1. Tenta notícias do Brasil
2. Se não encontrar, tenta EUA
3. Se falhar, usa dados de exemplo

## 🐛 Solução de Problemas

### Erro: "Page not found"
```bash
npm run build
php artisan cache:clear
```

### Erro: "Database connection failed"
Verifique as configurações no `.env` e certifique-se de que o banco existe.

### Erro: "API key invalid"
- Verifique se a chave da NewsAPI está correta
- O sistema funcionará com dados de exemplo se a API falhar

### Tela em branco
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
npm run build
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

# Verificar status das migrações
php artisan migrate:status

# Executar testes
php artisan test

# Acessar Tinker
php artisan tinker
```

## 🌐 Endpoints da API

### NewsAPI
- **Destaques**: `/v2/top-headlines`
- **Busca**: `/v2/everything`
- **Categorias**: `/v2/top-headlines?category={categoria}`

### Rotas da Aplicação
- `GET /news` - Página principal
- `POST /news/search` - Busca de notícias
- `GET /news/category/{category}` - Notícias por categoria
- `GET /history` - Histórico de buscas

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

---

**Desenvolvido com ❤️ usando Laravel + Vue.js + Inertia.js**
