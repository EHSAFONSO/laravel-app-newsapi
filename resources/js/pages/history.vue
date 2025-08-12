<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header Moderno com Glassmorphism -->
    <header class="backdrop-blur-md bg-white/80 border-b border-gray-200/50 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <!-- Logo e Nome -->
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
              </svg>
            </div>
            <h1 class="text-2xl font-bold gradient-text-blue">
              Portal de Notícias
            </h1>
          </div>
          
          <!-- Desktop Navigation -->
          <nav class="hidden md:flex space-x-1">
            <a href="/news" class="relative px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors group">
              Início
              <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="/history" class="relative px-4 py-2 text-sm font-medium text-blue-600 transition-colors group">
              Histórico
              <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600"></span>
            </a>
          </nav>
          
          <!-- Desktop Actions -->
          <div class="hidden md:flex items-center space-x-3">
            <a href="/" class="btn-secondary hover-scale">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Tela Inicial
            </a>
            <a href="/news" class="btn-secondary hover-scale">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Voltar
            </a>
          </div>
          
          <!-- Mobile Menu Button -->
          <div class="md:hidden">
            <button 
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 focus-ring"
            >
              <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="md:hidden" :class="mobileMenuOpen ? 'block' : 'hidden'">
          <div class="py-4 border-t border-gray-200/50">
            <nav class="flex flex-col space-y-2">
              <a href="/news" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                Início
              </a>
              <a href="/history" class="text-blue-600 px-3 py-2 text-sm font-medium border-l-4 border-blue-600 bg-blue-50 rounded-r-lg">
                Histórico
              </a>
            </nav>
            <div class="mt-4 pt-4 border-t border-gray-200/50">
              <a href="/" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors block">
                Tela Inicial
              </a>
              <a href="/news" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors block mt-2">
                Voltar
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      
      <!-- Search Bar Moderna -->
      <div class="mb-12 fade-in-up">
        <div class="max-w-2xl mx-auto">
          <form @submit.prevent="searchNews" class="relative">
            <div class="flex items-center bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl px-6 py-4 shadow-lg hover:shadow-xl transition-all duration-300 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20">
              <svg class="w-6 h-6 text-gray-400 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input
                v-model="searchForm.title"
                type="text"
                placeholder="Buscar notícias..."
                class="flex-1 text-lg border-none outline-none bg-transparent min-w-0 placeholder-gray-400"
                required
              >
              <button
                type="submit"
                :disabled="searchForm.processing"
                class="ml-4 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-medium transition-all duration-200 hover:from-blue-600 hover:to-blue-700 disabled:opacity-50 disabled:cursor-not-allowed hover:scale-105 focus-ring"
              >
                <span v-if="searchForm.processing" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Buscando...
                </span>
                <span v-else>Buscar</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Search History -->
      <div class="mb-12 fade-in-up" style="animation-delay: 0.2s;">
        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 flex items-center">
          <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
          </div>
          <span class="gradient-text">Buscas Anteriores</span>
          <span class="ml-3 text-sm text-gray-500 font-normal bg-gray-100 px-3 py-1 rounded-full">({{ searchHistory.length }} buscas)</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="(search, index) in searchHistory" 
            :key="`history-${index}-${search.id}`"
            class="news-card group"
          >
            <div class="p-6">
              <div class="flex items-center justify-between mb-3">
                <span class="text-lg font-semibold text-gray-900">{{ search.title }}</span>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ formatDate(search.created_at) }}</span>
              </div>
              <a 
                :href="`/news/search?title=${encodeURIComponent(search.title)}`"
                class="inline-flex items-center text-sm text-blue-600 font-medium hover:text-blue-700 transition-colors"
              >
                Buscar novamente
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent News Section -->
      <div v-if="recentNews && recentNews.success && recentNews.articles && recentNews.articles.length > 0" class="mb-8 fade-in-up" style="animation-delay: 0.3s;">
        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 flex items-center">
          <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <span class="gradient-text">Notícias Recentes</span>
          <span class="ml-3 text-sm text-gray-500 font-normal bg-gray-100 px-3 py-1 rounded-full">({{ recentNews.totalResults || recentNews.articles.length }} notícias)</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 news-grid">
          <article 
            v-for="(article, index) in recentNews.articles" 
            :key="`history-search-${index}-${article.url}`"
            class="news-card group"
          >
            <!-- Badge de categoria -->
            <div class="absolute top-4 left-4 z-10">
              <span class="badge badge-primary">
                {{ getCategoryFromTitle(article.title) }}
              </span>
            </div>
            
            <!-- Imagem -->
            <div class="relative h-48 overflow-hidden">
              <div v-if="article.urlToImage && !imageErrors[`history-${index}`]" class="w-full h-full">
                <img 
                  :src="article.urlToImage" 
                  :alt="article.title"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                  @error="handleImageError(`history-${index}`)"
                  @load="handleImageLoad(`history-${index}`)"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
              </div>
              <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                <div class="text-center">
                  <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                  </svg>
                  <div class="text-sm font-semibold text-gray-600">
                    {{ getCategoryFromTitle(article.title) }}
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-6">
              <div class="flex items-center gap-2 mb-3">
                <span class="text-xs text-gray-500 font-medium">{{ article.source?.name || 'Fonte' }}</span>
                <span class="text-gray-300">•</span>
                <span class="text-xs text-gray-500">{{ formatDate(article.publishedAt) }}</span>
              </div>
              <h4 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors mb-3">
                <a :href="article.url" target="_blank" rel="noopener noreferrer" class="text-inherit no-underline">
                  {{ article.title }}
                </a>
              </h4>
              <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                {{ article.description || 'Descrição não disponível' }}
              </p>
              <a 
                :href="article.url" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center text-sm text-blue-600 font-medium hover:text-blue-700 transition-colors"
              >
                Ler mais
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>
          </article>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="!searchHistory && !recentNews" class="text-center py-16 fade-in-up">
        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="animate-spin w-8 h-8 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        <p class="text-gray-600 text-lg">Carregando histórico...</p>
      </div>

      <!-- No Results Message -->
      <div v-if="searchHistory && searchHistory.length === 0" class="text-center py-16 fade-in-up">
        <div class="max-w-md mx-auto">
          <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-3">Nenhuma busca encontrada</h3>
          <p class="text-gray-600 mb-8 text-lg">
            Faça sua primeira busca para começar a construir seu histórico.
          </p>
          <a href="/news" class="btn-primary hover-scale">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Fazer Primeira Busca
          </a>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'

const props = defineProps({
  searchHistory: {
    type: Array,
    default: () => []
  },
  recentNews: {
    type: Object,
    default: () => ({})
  }
})

const searchForm = useForm({
  title: ''
})

const imageErrors = reactive({})
const mobileMenuOpen = ref(false)

const searchNews = () => {
  if (!searchForm.title.trim()) return
  window.location.href = `/news/search?title=${encodeURIComponent(searchForm.title)}`
}

const formatDate = (dateString) => {
  if (!dateString) return 'Data não disponível'
  try {
    return new Date(dateString).toLocaleDateString('pt-BR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch (error) {
    return 'Data não disponível'
  }
}

const handleImageError = (imageKey) => {
  imageErrors[imageKey] = true
}

const handleImageLoad = (imageKey) => {
  imageErrors[imageKey] = false
}

const getCategoryFromTitle = (title) => {
  if (!title) return 'Notícia'
  
  const titleLower = title.toLowerCase()
  
  // Priorize categorias mais específicas ou diretas
  if (titleLower.includes('tecnologia') || titleLower.includes('tech') || titleLower.includes('ai') || titleLower.includes('inteligência artificial') || titleLower.includes('inovação') || titleLower.includes('digital')) {
    return 'Tecnologia'
  }
  
  if (titleLower.includes('economia') || titleLower.includes('negócio') || titleLower.includes('mercado') || titleLower.includes('financeiro') || titleLower.includes('recuperação') || titleLower.includes('bolsa')) {
    return 'Economia'
  }
  
  if (titleLower.includes('saúde') || titleLower.includes('medicina') || titleLower.includes('hospital') || titleLower.includes('vacinação')) {
    return 'Saúde'
  }
  
  if (titleLower.includes('esporte') || titleLower.includes('futebol') || titleLower.includes('olímpico')) {
    return 'Esporte'
  }
  
  if (titleLower.includes('política') || titleLower.includes('governo') || titleLower.includes('eleição')) {
    return 'Política'
  }
  
  if (titleLower.includes('entretenimento') || titleLower.includes('filme') || titleLower.includes('música')) {
    return 'Entretenimento'
  }
  
  if (titleLower.includes('ciência') || titleLower.includes('pesquisa') || titleLower.includes('descoberta')) {
    return 'Ciência'
  }
  
  return 'Notícia'
}

const getPlaceholderStyle = (article) => {
  const category = getCategoryFromTitle(article.title)
  
  const gradients = {
    'Tecnologia': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    'Economia': 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
    'Saúde': 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
    'Esporte': 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
    'Política': 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
    'Entretenimento': 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
    'Ciência': 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
    'Notícia': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
  }
  
  return {
    background: gradients[category] || gradients['Notícia']
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>