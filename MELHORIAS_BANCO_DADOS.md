# 🗄️ Melhorias para o Banco de Dados da Aplicação

## 📊 Análise da Estrutura Atual

### Tabelas Existentes:
- `news` - Notícias principais
- `news_images` - Imagens das notícias
- `searches` - Buscas realizadas
- `search_histories` - Histórico de buscas
- `users` - Usuários do sistema
- `cache` - Cache do Laravel
- `jobs` - Filas de trabalho

## 🚀 Melhorias Propostas

### 1. **Índices e Performance**

#### 1.1 Índices para Tabela `news`
```sql
-- Índices para melhorar performance de consultas
CREATE INDEX idx_news_published_at ON news(published_at DESC);
CREATE INDEX idx_news_category ON news(category);
CREATE INDEX idx_news_source_name ON news(source_name);
CREATE INDEX idx_news_author ON news(author);
CREATE INDEX idx_news_title ON news(title);
CREATE INDEX idx_news_category_published ON news(category, published_at DESC);
CREATE INDEX idx_news_source_published ON news(source_name, published_at DESC);
```

#### 1.2 Índices para Tabela `news_images`
```sql
-- Índices já existentes estão bons, mas podemos adicionar:
CREATE INDEX idx_news_images_url ON news_images(url);
CREATE INDEX idx_news_images_accessible ON news_images(is_accessible);
```

#### 1.3 Índices para Tabela `search_histories`
```sql
CREATE INDEX idx_search_histories_user_id ON search_histories(user_id);
CREATE INDEX idx_search_histories_title ON search_histories(title);
CREATE INDEX idx_search_histories_created_at ON search_histories(created_at DESC);
```

### 2. **Normalização e Relacionamentos**

#### 2.1 Tabela `sources` (Nova)
```php
Schema::create('sources', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('domain')->nullable();
    $table->string('logo_url')->nullable();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->integer('reliability_score')->default(50); // 0-100
    $table->timestamps();
    
    $table->index('name');
    $table->index('is_active');
    $table->index('reliability_score');
});
```

#### 2.2 Tabela `categories` (Nova)
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('slug')->unique();
    $table->string('display_name');
    $table->text('description')->nullable();
    $table->string('icon')->nullable();
    $table->string('color')->nullable();
    $table->boolean('is_active')->default(true);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
    
    $table->index('slug');
    $table->index('is_active');
    $table->index('sort_order');
});
```

#### 2.3 Tabela `authors` (Nova)
```php
Schema::create('authors', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->nullable();
    $table->string('bio')->nullable();
    $table->string('avatar_url')->nullable();
    $table->unsignedBigInteger('source_id')->nullable();
    $table->boolean('is_verified')->default(false);
    $table->timestamps();
    
    $table->foreign('source_id')->references('id')->on('sources')->onDelete('set null');
    $table->index('name');
    $table->index('is_verified');
});
```

### 3. **Melhorias na Tabela `news`**

#### 3.1 Adicionar Campos
```php
Schema::table('news', function (Blueprint $table) {
    // Relacionamentos
    $table->unsignedBigInteger('source_id')->nullable()->after('source_name');
    $table->unsignedBigInteger('author_id')->nullable()->after('author');
    $table->unsignedBigInteger('category_id')->nullable()->after('category');
    
    // Campos de qualidade
    $table->integer('read_time')->nullable()->after('content'); // Tempo de leitura em minutos
    $table->integer('word_count')->nullable()->after('read_time');
    $table->decimal('sentiment_score', 3, 2)->nullable()->after('word_count'); // -1 a 1
    $table->json('tags')->nullable()->after('sentiment_score');
    $table->json('metadata')->nullable()->after('tags'); // Dados adicionais
    
    // Campos de engajamento
    $table->integer('view_count')->default(0)->after('metadata');
    $table->integer('share_count')->default(0)->after('view_count');
    $table->integer('bookmark_count')->default(0)->after('share_count');
    
    // Campos de qualidade
    $table->boolean('is_featured')->default(false)->after('bookmark_count');
    $table->boolean('is_breaking')->default(false)->after('is_featured');
    $table->enum('content_quality', ['low', 'medium', 'high'])->default('medium')->after('is_breaking');
    $table->timestamp('featured_at')->nullable()->after('content_quality');
    
    // Campos de SEO
    $table->string('slug')->nullable()->after('title');
    $table->text('meta_description')->nullable()->after('slug');
    $table->json('meta_keywords')->nullable()->after('meta_description');
    
    // Índices
    $table->foreign('source_id')->references('id')->on('sources')->onDelete('set null');
    $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');
    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
    
    $table->index('slug');
    $table->index('is_featured');
    $table->index('is_breaking');
    $table->index('content_quality');
    $table->index('view_count');
    $table->index(['category_id', 'published_at']);
    $table->index(['source_id', 'published_at']);
    $table->index(['is_featured', 'published_at']);
});
```

### 4. **Tabelas de Analytics e Métricas**

#### 4.1 Tabela `news_views` (Nova)
```php
Schema::create('news_views', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('news_id');
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('ip_address')->nullable();
    $table->string('user_agent')->nullable();
    $table->string('referrer')->nullable();
    $table->string('session_id')->nullable();
    $table->timestamp('viewed_at');
    $table->integer('view_duration')->nullable(); // Segundos
    $table->boolean('is_unique')->default(true);
    $table->timestamps();
    
    $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    
    $table->index('news_id');
    $table->index('user_id');
    $table->index('viewed_at');
    $table->index('is_unique');
    $table->index(['news_id', 'viewed_at']);
});
```

#### 4.2 Tabela `news_shares` (Nova)
```php
Schema::create('news_shares', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('news_id');
    $table->unsignedBigInteger('user_id')->nullable();
    $table->enum('platform', ['facebook', 'twitter', 'linkedin', 'whatsapp', 'email', 'copy_link']);
    $table->string('ip_address')->nullable();
    $table->timestamp('shared_at');
    $table->timestamps();
    
    $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    
    $table->index('news_id');
    $table->index('platform');
    $table->index('shared_at');
});
```

#### 4.3 Tabela `news_bookmarks` (Nova)
```php
Schema::create('news_bookmarks', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('news_id');
    $table->unsignedBigInteger('user_id');
    $table->string('notes')->nullable();
    $table->json('tags')->nullable();
    $table->timestamps();
    
    $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
    $table->unique(['news_id', 'user_id']);
    $table->index('user_id');
    $table->index('created_at');
});
```

### 5. **Tabelas de Cache e Performance**

#### 5.1 Tabela `news_cache` (Nova)
```php
Schema::create('news_cache', function (Blueprint $table) {
    $table->id();
    $table->string('cache_key')->unique();
    $table->longText('cache_value');
    $table->timestamp('expires_at');
    $table->timestamps();
    
    $table->index('expires_at');
});
```

#### 5.2 Tabela `api_requests` (Nova)
```php
Schema::create('api_requests', function (Blueprint $table) {
    $table->id();
    $table->string('endpoint');
    $table->string('method');
    $table->integer('response_time'); // Milissegundos
    $table->integer('status_code');
    $table->text('request_data')->nullable();
    $table->text('response_data')->nullable();
    $table->string('ip_address')->nullable();
    $table->timestamp('requested_at');
    $table->timestamps();
    
    $table->index('endpoint');
    $table->index('status_code');
    $table->index('requested_at');
    $table->index('response_time');
});
```

### 6. **Tabelas de Configuração**

#### 6.1 Tabela `settings` (Nova)
```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value');
    $table->string('type')->default('string'); // string, integer, boolean, json
    $table->string('group')->default('general');
    $table->text('description')->nullable();
    $table->boolean('is_public')->default(false);
    $table->timestamps();
    
    $table->index('group');
    $table->index('is_public');
});
```

### 7. **Melhorias de Segurança**

#### 7.1 Tabela `failed_jobs` (Melhorar)
```php
Schema::table('failed_jobs', function (Blueprint $table) {
    $table->string('exception_class')->nullable()->after('exception');
    $table->json('context')->nullable()->after('exception_class');
});
```

#### 7.2 Tabela `audit_logs` (Nova)
```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('action'); // create, update, delete, view
    $table->string('model_type');
    $table->unsignedBigInteger('model_id');
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('ip_address')->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    
    $table->index('user_id');
    $table->index('action');
    $table->index('model_type');
    $table->index('created_at');
    $table->index(['model_type', 'model_id']);
});
```

## 🔧 Implementação das Melhorias

### Fase 1: Índices e Performance
1. Criar migration para adicionar índices
2. Executar em ambiente de desenvolvimento
3. Testar performance

### Fase 2: Normalização
1. Criar tabelas `sources`, `categories`, `authors`
2. Migrar dados existentes
3. Atualizar relacionamentos

### Fase 3: Analytics
1. Implementar tracking de views
2. Criar sistema de métricas
3. Dashboard de analytics

### Fase 4: Cache e Otimização
1. Implementar cache inteligente
2. Otimizar consultas
3. Monitoramento de performance

## 📈 Benefícios Esperados

### Performance
- **Consultas 50-80% mais rápidas** com índices adequados
- **Cache inteligente** reduzindo carga no banco
- **Paginação otimizada** para grandes volumes

### Funcionalidades
- **Analytics detalhados** de engajamento
- **Sistema de bookmarks** para usuários
- **Métricas de qualidade** de conteúdo
- **Auditoria completa** de ações

### Escalabilidade
- **Estrutura normalizada** para crescimento
- **Relacionamentos otimizados**
- **Cache distribuído**
- **Monitoramento de performance**

### SEO e UX
- **URLs amigáveis** com slugs
- **Meta tags** otimizadas
- **Tempo de leitura** estimado
- **Tags e categorização** avançada

## 🚀 Próximos Passos

1. **Revisar e aprovar** as melhorias propostas
2. **Criar migrations** em ordem de prioridade
3. **Implementar** em ambiente de desenvolvimento
4. **Testar** performance e funcionalidades
5. **Deploy** em produção com backup
6. **Monitorar** métricas e ajustar conforme necessário
