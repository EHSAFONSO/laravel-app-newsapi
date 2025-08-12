# 📝 Guia de Commits Semânticos

## 🎯 **Padrão de Commits**

```
<tipo>(<escopo>): <descrição>

[corpo opcional]

[rodapé opcional]
```

## 📋 **Tipos de Commit**

### **feat** - Nova funcionalidade
- Adiciona uma nova funcionalidade ao sistema
- Exemplo: `feat(database): add performance indexes to news table`

### **fix** - Correção de bug
- Corrige um bug ou problema
- Exemplo: `fix(vue): resolve $event warning in welcome page`

### **perf** - Melhoria de performance
- Melhora a performance sem alterar funcionalidades
- Exemplo: `perf(database): optimize news queries with composite indexes`

### **refactor** - Refatoração
- Refatora código sem alterar funcionalidades
- Exemplo: `refactor(components): extract accessibility menu to separate component`

### **docs** - Documentação
- Adiciona ou atualiza documentação
- Exemplo: `docs(database): add performance analysis guide`

### **style** - Formatação
- Alterações de formatação (espaços, ponto e vírgula, etc.)
- Exemplo: `style(vue): fix indentation in show.vue template`

### **test** - Testes
- Adiciona ou corrige testes
- Exemplo: `test(database): add performance analysis command tests`

### **chore** - Tarefas de manutenção
- Tarefas de build, configuração, etc.
- Exemplo: `chore(deps): update Laravel to version 11`

## 🗄️ **Commits para Melhorias do Banco de Dados**

### **Fase 1: Índices de Performance** ✅

```bash
# Commit principal dos índices
feat(database): add performance indexes to news table

# Detalhes dos índices implementados
- Add idx_news_published_at for date-based queries
- Add idx_news_category for category filtering
- Add idx_news_source_name for source filtering
- Add idx_news_author for author filtering
- Add idx_news_title for title searches
- Add composite indexes for complex queries
```

```bash
# Comando de análise
feat(console): add database performance analysis command

# Funcionalidades do comando
- Analyze table sizes and record counts
- Test query performance with timing
- Generate improvement suggestions
- Support detailed analysis mode
```

### **Fase 2: Normalização** (Próximos commits)

```bash
# Tabela sources
feat(database): create sources table for data normalization

# Tabela categories
feat(database): create categories table with SEO-friendly slugs

# Tabela authors
feat(database): create authors table with verification system

# Migração de dados
feat(database): migrate existing data to normalized tables

# Relacionamentos
feat(database): add foreign key relationships to news table
```

### **Fase 3: SEO e UX** (Próximos commits)

```bash
# Campos SEO
feat(database): add SEO fields to news table

# Slugs automáticos
feat(models): implement automatic slug generation for news

# Tempo de leitura
feat(models): add read time calculation for news articles

# Sistema de tags
feat(database): add tags system for news categorization
```

### **Fase 4: Analytics** (Próximos commits)

```bash
# Tracking de views
feat(database): create news_views table for analytics

# Sistema de compartilhamento
feat(database): create news_shares table for social tracking

# Favoritos
feat(database): create news_bookmarks table for user favorites

# Dashboard analytics
feat(admin): add analytics dashboard for news metrics
```

## 🔧 **Commits para Melhorias da Interface**

### **Menu de Acessibilidade**
```bash
feat(ui): add accessibility menu component

# Funcionalidades implementadas
- High contrast mode toggle
- Font size adjustment (A-, A, A+)
- Increased spacing option
- Reduced motion preference
- Visible focus indicators
- Settings persistence in localStorage
```

### **Menu Mobile Responsivo**
```bash
feat(ui): implement responsive mobile menu for welcome page

# Funcionalidades implementadas
- Hamburger menu for mobile devices
- Collapsible navigation with smooth transitions
- Accessibility menu integration
- Touch-friendly interface
- Responsive breakpoints (md:)
```

### **Correções de Bugs**
```bash
fix(vue): resolve $event warning in welcome page image handling

# Problema resolvido
- Replace incorrect $event usage with proper error handling
- Implement imageErrors reactive state
- Add data-image-key attributes for tracking
- Improve image fallback logic
```

## 📊 **Commits para Performance**

### **Cache e Otimização**
```bash
perf(controller): implement caching for news queries

# Melhorias implementadas
- Add 5-minute cache for main news page
- Implement cache keys with pagination support
- Reduce database load for frequent queries
- Improve response times for news listings
```

### **Rate Limiting**
```bash
feat(security): add rate limiting to search functionality

# Implementação
- Limit search attempts to 10 per minute per IP
- Add proper error handling for rate limit exceeded
- Implement exponential backoff for retries
- Add user-friendly error messages
```

## 🎨 **Commits para UX/UI**

### **Ícones de Categoria**
```bash
feat(ui): add category icons for news without images

# Implementação
- Add getCategoryIconStyle function for dynamic icons
- Implement SVG icons with category-specific colors
- Add fallback system for missing images
- Improve visual consistency across pages
```

### **Filtros Avançados**
```bash
feat(ui): add advanced filters to news listing page

# Funcionalidades
- Recent news filter (Mais Recentes)
- Popular news filter (Mais Populares)
- Image filter (Com Imagem)
- Database filter (Do Banco)
- Reactive filter state management
```

## 📝 **Exemplos de Commits Completos**

### **Commit Principal dos Índices**
```bash
feat(database): add performance indexes to news table

- Add 8 performance indexes for optimized queries
- Improve query speed by 10-30% for common operations
- Support filtering by category, date, and source
- Enable efficient sorting and searching

Closes #123
```

### **Commit do Menu de Acessibilidade**
```bash
feat(ui): add accessibility menu component with persistent settings

- Implement high contrast mode toggle
- Add font size adjustment (A-, A, A+)
- Include increased spacing and reduced motion options
- Add visible focus indicators for keyboard navigation
- Persist user preferences in localStorage
- Integrate with mobile menu for responsive design

Improves accessibility compliance and user experience
```

### **Commit de Correção**
```bash
fix(vue): resolve $event warning in welcome page image handling

- Replace incorrect $event usage with proper error handling
- Implement imageErrors reactive state for tracking
- Add data-image-key attributes for image identification
- Improve image fallback logic with category placeholders
- Fix Vue compiler warnings in development

Resolves console warnings and improves code quality
```

## 🚀 **Padrão de Branch Naming**

### **Feature Branches**
```bash
feature/database-performance-indexes
feature/accessibility-menu
feature/mobile-responsive-menu
feature/news-analytics
```

### **Bug Fix Branches**
```bash
fix/vue-event-warning
fix/mobile-menu-styling
fix/database-query-performance
```

### **Refactor Branches**
```bash
refactor/news-controller-caching
refactor/component-structure
refactor/database-migrations
```

## 📋 **Checklist para Commits**

### **Antes do Commit**
- [ ] Código testado e funcionando
- [ ] Documentação atualizada
- [ ] Testes passando
- [ ] Linting sem erros

### **Estrutura do Commit**
- [ ] Tipo correto (feat, fix, perf, etc.)
- [ ] Escopo apropriado (database, ui, api, etc.)
- [ ] Descrição clara e concisa
- [ ] Corpo detalhado (se necessário)
- [ ] Referência a issues (se aplicável)

### **Após o Commit**
- [ ] Push para branch remota
- [ ] Criar Pull Request (se aplicável)
- [ ] Atualizar documentação
- [ ] Notificar equipe (se necessário)

## 🎯 **Benefícios dos Commits Semânticos**

### **Para Desenvolvedores**
- **Histórico claro** do que foi implementado
- **Facilita debugging** e troubleshooting
- **Melhora colaboração** em equipe
- **Automatiza changelog** generation

### **Para o Projeto**
- **Versionamento semântico** automático
- **Release notes** detalhadas
- **Rastreabilidade** de mudanças
- **Documentação** viva do projeto

---

**💡 Dica**: Use `git log --oneline` para ver o histórico de commits e `git log --grep="feat"` para filtrar por tipo de commit.
