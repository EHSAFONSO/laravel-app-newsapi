<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Moderno com Glassmorphism -->
    <header class="backdrop-blur-md bg-white/80 dark:bg-gray-900/80 border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50">
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
            <a href="/news" class="relative px-4 py-2 text-sm font-medium text-blue-600 transition-colors group">
              Início
              <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600"></span>
            </a>
            <a href="/history" class="relative px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors group">
              Histórico
              <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
          </nav>
          
          <!-- Desktop Actions -->
          <div class="hidden md:flex items-center space-x-3">
            <ThemeToggle />
            <a href="/" class="btn-secondary hover-scale">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Tela Inicial
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
              <a href="/news" class="text-blue-600 px-3 py-2 text-sm font-medium border-l-4 border-blue-600 bg-blue-50 rounded-r-lg">
                Início
              </a>
              <a href="/history" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                Histórico
              </a>
              <a href="/" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                Tela Inicial
              </a>
            </nav>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      
      <!-- API Error Message -->
      <div v-if="news && news.error" class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-yellow-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <div>
            <h3 class="text-sm font-medium text-yellow-800">Limite da API Excedido</h3>
            <p class="text-sm text-yellow-700 mt-1">{{ news.error }}</p>
            <p class="text-xs text-yellow-600 mt-2">Os dados exibidos são de exemplo. Aguarde algumas horas para o reset do limite.</p>
          </div>
        </div>
      </div>
      
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
        
        <!-- Advanced Filters -->
        <div class="mt-6 max-w-2xl mx-auto fade-in-up" style="animation-delay: 0.2s;">
          <div class="flex flex-wrap gap-3 justify-center">
            <button 
              @click="toggleFilter('recent')"
              :class="activeFilters.recent ? 'bg-blue-100 text-blue-700 border-blue-300 shadow-md' : 'bg-white/80 backdrop-blur-sm text-gray-700 border-gray-200/50'"
              class="px-4 py-2 text-sm font-medium border rounded-full hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 hover:scale-105 focus-ring"
            >
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Mais Recentes
            </button>
            <button 
              @click="toggleFilter('popular')"
              :class="activeFilters.popular ? 'bg-blue-100 text-blue-700 border-blue-300 shadow-md' : 'bg-white/80 backdrop-blur-sm text-gray-700 border-gray-200/50'"
              class="px-4 py-2 text-sm font-medium border rounded-full hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 hover:scale-105 focus-ring"
            >
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              Mais Populares
            </button>
            <button 
              @click="toggleFilter('withImage')"
              :class="activeFilters.withImage ? 'bg-blue-100 text-blue-700 border-blue-300 shadow-md' : 'bg-white/80 backdrop-blur-sm text-gray-700 border-gray-200/50'"
              class="px-4 py-2 text-sm font-medium border rounded-full hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 hover:scale-105 focus-ring"
            >
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Com Imagem
            </button>
            <button 
              @click="toggleFilter('fromDatabase')"
              :class="activeFilters.fromDatabase ? 'bg-blue-100 text-blue-700 border-blue-300 shadow-md' : 'bg-white/80 backdrop-blur-sm text-gray-700 border-gray-200/50'"
              class="px-4 py-2 text-sm font-medium border rounded-full hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 hover:scale-105 focus-ring"
            >
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
              </svg>
              Do Banco
            </button>
          </div>
        </div>
      </div>

      <!-- Categories -->
      <div class="mb-12 fade-in-up" style="animation-delay: 0.3s;">
        <div class="flex flex-wrap gap-3">
          <a
            v-for="(label, key) in categories"
            :key="key"
            :href="key === 'general' ? '/news' : `/news/category/${key}`"
            class="group px-4 py-2 bg-white/80 backdrop-blur-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:border-blue-200 border border-gray-200/50 transition-all duration-200 text-sm font-medium hover:scale-105 focus-ring"
            :class="{ 'bg-blue-100 text-blue-700 border-blue-300 shadow-md': currentCategory === key }"
          >
            <span class="flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              {{ label }}
            </span>
          </a>
        </div>
      </div>

      <!-- News Section -->
      <div v-if="news && news.success && news.articles && news.articles.length > 0" class="mb-8">
        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 flex items-center">
          <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd" />
            </svg>
          </div>
          <span class="gradient-text">Últimas Notícias</span>
          <span class="ml-3 text-sm text-gray-500 font-normal bg-gray-100 px-3 py-1 rounded-full">({{ news.totalResults || news.articles.length }} notícias)</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 news-grid">
          <article 
            v-for="(article, index) in news.articles" 
            :key="`news-${index}-${article.id || article.url}`"
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
              <div v-if="article.urlToImage && !imageErrors[`news-${index}`]" class="w-full h-full">
                <img 
                  :src="article.urlToImage" 
                  :alt="article.title"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                  @error="handleImageError(`news-${index}`)"
                  @load="handleImageLoad(`news-${index}`)"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
              </div>
              <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200" :class="getCategoryIconStyle(article.category).bg">
                <div class="text-center">
                  <svg class="w-12 h-12 mx-auto mb-2" :class="getCategoryIconStyle(article.category).text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getCategoryIconStyle(article.category).path" />
                  </svg>
                  <div class="text-sm font-semibold" :class="getCategoryIconStyle(article.category).text">
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
                <span v-if="article.fromDatabase" class="badge badge-success text-xs">DB</span>
              </div>
              <h4 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors mb-3">
                <a :href="`/news/${article.id}`" class="text-inherit no-underline">
                  {{ article.title }}
                </a>
              </h4>
              <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                {{ article.description || 'Descrição não disponível' }}
              </p>
              <a 
                :href="`/news/${article.id}`"
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

        <!-- Pagination -->
        <div v-if="news.totalPages > 1" class="mt-12 flex justify-center fade-in-up" style="animation-delay: 0.4s;">
          <nav class="flex items-center space-x-3">
            <a 
              v-if="news.currentPage > 1"
              :href="`/news?page=${news.currentPage - 1}`"
              class="btn-secondary hover-scale"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Anterior
            </a>
            <span class="px-4 py-2 text-sm text-gray-700 bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-lg">
              Página {{ news.currentPage }} de {{ news.totalPages }}
            </span>
            <a 
              v-if="news.currentPage < news.totalPages"
              :href="`/news?page=${news.currentPage + 1}`"
              class="btn-secondary hover-scale"
            >
              Próxima
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </nav>
        </div>
      </div>

      <!-- Loading State -->
      <div v-else-if="!news || !news.success" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Carregando notícias...</p>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma notícia encontrada</h3>
        <p class="text-gray-600">Tente buscar por um termo diferente ou verifique as categorias acima.</p>
      </div>

      <!-- Error Message -->
      <div v-if="news && news.error" class="bg-red-50 border border-red-200 rounded-lg p-6">
        <p class="text-red-700">
          {{ news.error }}
        </p>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, reactive, onMounted } from 'vue'
import ThemeToggle from '../../components/ThemeToggle.vue'

const props = defineProps({
  news: {
    type: Object,
    default: () => ({})
  },
  currentCategory: {
    type: String,
    default: 'general'
  },
  currentPage: {
    type: Number,
    default: 1
  },
  categories: {
    type: Object,
    default: () => ({
      'general': 'Geral',
      'business': 'Negócios',
      'technology': 'Tecnologia',
      'sports': 'Esportes',
      'entertainment': 'Entretenimento',
      'health': 'Saúde',
      'science': 'Ciência'
    })
  }
})

// Debug: Log props on mount
onMounted(() => {
  console.log('News Index Component Mounted')
  console.log('Props received:', props)
  console.log('News data:', props.news)
  console.log('News success:', props.news?.success)
  console.log('News articles count:', props.news?.articles?.length)
})

const searchForm = useForm({
  title: ''
})

const imageErrors = reactive({})
const mobileMenuOpen = ref(false)
const activeFilters = reactive({
  recent: false,
  popular: false,
  withImage: false,
  fromDatabase: false
})

const searchNews = () => {
  if (!searchForm.title.trim()) return
  window.location.href = `/news/search?title=${encodeURIComponent(searchForm.title)}`
}

const toggleFilter = (filter) => {
  activeFilters[filter] = !activeFilters[filter]
  // Aqui você pode implementar a lógica de filtro
  console.log('Filtro ativado:', filter, activeFilters[filter])
}

const formatDate = (dateString) => {
  if (!dateString) return 'Data não disponível'
  try {
    return new Date(dateString).toLocaleDateString('pt-BR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
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

const getCategoryIconStyle = (category) => {
  const styles = {
    'business': {
      bg: 'bg-green-100',
      text: 'text-green-600',
      path: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    'technology': {
      bg: 'bg-blue-100',
      text: 'text-blue-600',
      path: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
    },
    'sports': {
      bg: 'bg-orange-100',
      text: 'text-orange-600',
      path: 'M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m-6 0h6m-6 0a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V6a2 2 0 00-2-2M9 12l2 2 4-4'
    },
    'entertainment': {
      bg: 'bg-purple-100',
      text: 'text-purple-600',
      path: 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'
    },
    'health': {
      bg: 'bg-red-100',
      text: 'text-red-600',
      path: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'
    },
    'science': {
      bg: 'bg-indigo-100',
      text: 'text-indigo-600',
      path: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'
    },
    'general': {
      bg: 'bg-gray-100',
      text: 'text-gray-600',
      path: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'
    }
  }
  
  return styles[category] || styles['general']
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