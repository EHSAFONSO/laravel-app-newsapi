<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-8">
            <h1 class="text-2xl font-bold text-gray-900">Portal de Notícias</h1>
            <nav class="flex space-x-6">
              <a href="/news" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                Início
              </a>
              <a href="/history" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                Histórico
              </a>
            </nav>
          </div>
          <a href="/history" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Histórico
          </a>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      
      <!-- Search Results Header -->
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
          Resultados da busca: "{{ searchTerm }}"
        </h2>
        <p class="text-gray-600">
          {{ searchResults.totalResults || searchResults.articles?.length || 0 }} notícias encontradas
        </p>
      </div>

      <!-- Search Bar -->
      <div class="mb-8">
        <div class="max-w-2xl mx-auto">
          <form @submit.prevent="searchNews" class="relative">
            <div class="flex items-center bg-white border border-gray-300 rounded-lg px-4 py-3 hover:border-gray-400 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-colors">
              <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input
                v-model="searchForm.title"
                type="text"
                placeholder="Buscar notícias..."
                class="flex-1 text-base border-none outline-none bg-transparent min-w-0"
                required
              >
              <button
                type="submit"
                :disabled="searchForm.processing"
                class="ml-3 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 font-medium transition-colors text-sm flex-shrink-0"
              >
                <span v-if="searchForm.processing">Buscando...</span>
                <span v-else>Buscar</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Search Results -->
      <div v-if="searchResults && searchResults.success && searchResults.articles && searchResults.articles.length > 0" class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <article 
            v-for="(article, index) in searchResults.articles" 
            :key="`search-${index}-${article.url}`"
            class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
          >
            <!-- Imagem -->
            <div class="relative h-48 bg-gray-200">
              <div v-if="article.urlToImage && !imageErrors[`search-${index}`]" class="w-full h-full">
                <img 
                  :src="article.urlToImage" 
                  :alt="article.title"
                  class="w-full h-full object-cover"
                  @error="handleImageError(`search-${index}`)"
                  @load="handleImageLoad(`search-${index}`)"
                >
              </div>
              <div v-else class="w-full h-full flex items-center justify-center" :style="getPlaceholderStyle(article)">
                <div class="text-center text-white font-semibold text-lg px-4">
                  {{ getCategoryFromTitle(article.title) }}
                </div>
              </div>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-4">
              <div class="flex items-center space-x-2 mb-2">
                <span class="text-xs text-gray-500 font-medium">{{ article.source?.name || 'Fonte' }}</span>
                <span class="text-gray-300">•</span>
                <span class="text-xs text-gray-500">{{ formatDate(article.publishedAt) }}</span>
              </div>
              <h4 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 leading-tight">
                <a :href="article.url" target="_blank" rel="noopener noreferrer" class="hover:text-blue-600 transition-colors">
                  {{ article.title }}
                </a>
              </h4>
              <p class="text-xs text-gray-600 line-clamp-2 mb-3">
                {{ article.description || 'Descrição não disponível' }}
              </p>
              <a 
                :href="article.url" 
                target="_blank" 
                rel="noopener noreferrer"
                class="text-xs text-blue-600 hover:text-blue-800 font-medium"
              >
                Ler mais →
              </a>
            </div>
          </article>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="!searchResults" class="text-center py-16">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Buscando notícias...</p>
      </div>

      <!-- No Results Message -->
      <div v-if="searchResults && searchResults.success && (!searchResults.articles || searchResults.articles.length === 0)" class="text-center py-16">
        <div class="max-w-md mx-auto">
          <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhuma notícia encontrada</h3>
          <p class="text-gray-600 mb-6">
            Tente buscar por outros termos ou verificar outras categorias.
          </p>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="searchResults && searchResults.error" class="bg-red-50 border border-red-200 rounded-lg p-6">
        <p class="text-red-700">{{ searchResults.error }}</p>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'

const props = defineProps({
  searchResults: {
    type: Object,
    default: () => ({})
  },
  searchTerm: {
    type: String,
    default: ''
  },
  currentPage: {
    type: Number,
    default: 1
  }
})

const searchForm = useForm({
  title: props.searchTerm
})

const imageErrors = reactive({})

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
  
  if (titleLower.includes('tecnologia') || titleLower.includes('tech') || titleLower.includes('ai') || titleLower.includes('inteligência artificial')) {
    return 'Tecnologia'
  }
  if (titleLower.includes('economia') || titleLower.includes('negócio') || titleLower.includes('mercado') || titleLower.includes('financeiro')) {
    return 'Economia'
  }
  if (titleLower.includes('saúde') || titleLower.includes('medicina') || titleLower.includes('hospital')) {
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
