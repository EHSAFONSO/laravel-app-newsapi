<template>
  <div class="min-h-screen bg-white">
    <!-- Header -->
    <header class="border-b border-gray-200 bg-white sticky top-0 z-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-8">
            <h1 class="text-2xl font-bold text-gray-900">Portal de Notícias</h1>
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
          <div class="flex items-center space-x-4">
            <a 
              href="/news" 
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-full hover:bg-gray-50 transition-colors text-sm font-medium"
            >
              Voltar ao Início
            </a>
            <a 
              href="/history" 
              class="px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition-colors text-sm font-medium"
            >
              Ver Histórico
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
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            {{ categoryLabel }}
          </h2>
          <p class="text-gray-600 text-lg">
            {{ categoryNews.totalResults || 0 }} notícias encontradas
          </p>
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
                  <a :href="article.url" target="_blank" rel="noopener noreferrer">
                    {{ article.title }}
                  </a>
                </h4>
                <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                  {{ article.description }}
                </p>
                <div class="flex items-center justify-between">
                  <a 
                    :href="article.url" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="text-sm font-medium text-gray-900 hover:text-gray-700 transition-colors"
                  >
                    Ler mais →
                  </a>
                </div>
              </div>
              <div class="hidden md:block flex-shrink-0">
                <img 
                  v-if="article.urlToImage" 
                  :src="article.urlToImage" 
                  :alt="article.title"
                  class="w-32 h-24 object-cover rounded-lg"
                  @error="handleImageError"
                >
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

const handleImageError = (event) => {
  event.target.style.display = 'none'
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
