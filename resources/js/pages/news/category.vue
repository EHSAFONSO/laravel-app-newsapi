<template>
  <div class="min-h-screen bg-white">
    <!-- Header -->
    <header class="border-b border-gray-200 bg-white sticky top-0 z-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-8">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Portal de Notícias</h1>
              <p class="text-sm text-blue-600 font-medium">{{ categoryLabel }}</p>
            </div>
            <nav class="flex space-x-6">
              <a 
                href="/news" 
                class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors"
              >
                Início
              </a>
              <a 
                href="/history" 
                class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors"
              >
                Histórico
              </a>
            </nav>
          </div>
          <div class="flex items-center space-x-3">
            <a 
              href="/" 
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Tela Inicial
            </a>
            <a 
              href="/news" 
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-full hover:bg-gray-50 transition-colors text-sm font-medium"
            >
              Voltar ao Início
            </a>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Category Header -->
      <div class="mb-12">
        <div class="text-center">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" :class="getCategoryIconStyle().bg">
            <svg class="w-8 h-8" :class="getCategoryIconStyle().text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getCategoryIconStyle().path" />
            </svg>
          </div>
          <h2 class="text-4xl font-bold text-gray-900 mb-4">
            {{ categoryLabel }}
          </h2>
          <p class="text-gray-600 text-lg">
            {{ categoryNews.totalResults || 0 }} notícias encontradas
          </p>
          <div class="mt-4 flex justify-center">
            <a 
              href="/news" 
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Ver todas as notícias
            </a>
          </div>
        </div>
      </div>

      <!-- Category News -->
      <div v-if="categoryNews && categoryNews.success && categoryNews.articles && categoryNews.articles.length > 0" class="mb-16">
        <div class="space-y-8">
          <article 
            v-for="article in categoryNews.articles" 
            :key="article.url"
            class="border-b border-gray-200 pb-8 last:border-b-0"
          >
            <div class="flex gap-6">
              <div class="flex-1">
                <div class="flex items-center space-x-2 mb-3">
                  <span class="text-sm text-gray-500">{{ article.source?.name }}</span>
                  <span class="text-gray-300">•</span>
                  <span class="text-sm text-gray-500">{{ formatDate(article.publishedAt) }}</span>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3 leading-tight hover:text-gray-700 transition-colors">
                  <a :href="`/news/${article.id}`">
                    {{ article.title }}
                  </a>
                </h4>
                <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                  {{ article.description }}
                </p>
                <div class="flex items-center justify-between">
                  <a 
                    :href="`/news/${article.id}`"
                    class="text-sm font-medium text-gray-900 hover:text-gray-700 transition-colors"
                  >
                    Ler mais →
                  </a>
                </div>
              </div>
              <div class="hidden md:block flex-shrink-0">
                <img 
                  v-if="article.urlToImage && !imageErrors[`category-${article.id}`]" 
                  :src="article.urlToImage" 
                  :alt="article.title"
                  class="w-32 h-24 object-cover rounded-lg"
                  @error="handleImageError(`category-${article.id}`)"
                >
                <div 
                  v-else 
                  class="w-32 h-24 rounded-lg flex items-center justify-center"
                  :class="getCategoryIconStyle().bg"
                >
                  <svg class="w-8 h-8" :class="getCategoryIconStyle().text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getCategoryIconStyle().path" />
                  </svg>
                </div>
              </div>
            </div>
          </article>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="!categoryNews" class="text-center py-16">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-black mx-auto mb-4"></div>
        <p class="text-gray-600">Carregando notícias...</p>
      </div>

      <!-- No Results Message -->
      <div v-if="categoryNews && categoryNews.success && (!categoryNews.articles || categoryNews.articles.length === 0)" class="text-center py-16">
        <div class="max-w-md mx-auto">
          <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhuma notícia encontrada</h3>
          <p class="text-gray-600 mb-6">
            Não há notícias disponíveis nesta categoria no momento.
          </p>
          <a 
            href="/news" 
            class="inline-block px-6 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-colors font-medium"
          >
            Voltar ao início
          </a>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="categoryNews && categoryNews.error" class="bg-red-50 border border-red-200 rounded-lg p-6">
        <p class="text-red-700">
          {{ categoryNews.error }}
        </p>
      </div>

      <!-- Pagination -->
      <div v-if="categoryNews && categoryNews.totalPages && categoryNews.totalPages > 1" class="flex justify-center mt-12">
        <nav class="flex space-x-2">
          <a
            v-if="currentPage > 1"
            :href="`/news/category/${category}?page=${currentPage - 1}`"
            class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
          >
            ← Anterior
          </a>
          
          <span 
            v-for="page in getPageNumbers()" 
            :key="page"
            class="px-4 py-2 rounded-lg"
            :class="page === currentPage ? 'bg-black text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'"
          >
            <a v-if="page !== currentPage" :href="`/news/category/${category}?page=${page}`">
              {{ page }}
            </a>
            <span v-else>{{ page }}</span>
          </span>
          
          <a
            v-if="currentPage < (categoryNews.totalPages || 1)"
            :href="`/news/category/${category}?page=${currentPage + 1}`"
            class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
          >
            Próxima →
          </a>
        </nav>
      </div>
    </main>
  </div>
</template>

<script setup>
import { reactive } from 'vue'

const props = defineProps({
  categoryNews: {
    type: Object,
    default: () => ({})
  },
  category: {
    type: String,
    default: 'general'
  },
  categoryLabel: {
    type: String,
    default: 'Notícias'
  },
  currentPage: {
    type: Number,
    default: 1
  }
})

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const imageErrors = reactive({})

const handleImageError = (imageKey) => {
  imageErrors[imageKey] = true
}

const getCategoryIconStyle = () => {
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
    }
  }
  
  return styles[props.category] || {
    bg: 'bg-gray-100',
    text: 'text-gray-600',
    path: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'
  }
}

const getPageNumbers = () => {
  const totalPages = props.categoryNews.totalPages || 1
  const current = props.currentPage
  const pages = []
  
  if (totalPages <= 7) {
    for (let i = 1; i <= totalPages; i++) {
      pages.push(i)
    }
  } else {
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(totalPages)
    } else if (current >= totalPages - 3) {
      pages.push(1)
      pages.push('...')
      for (let i = totalPages - 4; i <= totalPages; i++) {
        pages.push(i)
      }
    } else {
      pages.push(1)
      pages.push('...')
      for (let i = current - 1; i <= current + 1; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(totalPages)
    }
  }
  
  return pages
}
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
