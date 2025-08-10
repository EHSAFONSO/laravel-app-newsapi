<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-3xl font-bold text-gray-900">Portal de Notícias</h1>
          <nav class="flex space-x-4">
            <Link 
              href="/news" 
              class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
              :class="{ 'bg-gray-100': $page.url === '/news' }"
            >
              Início
            </Link>
            <Link 
              href="/history" 
              class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              Histórico
            </Link>
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        
        <!-- Search Form -->
        <div class="mb-8">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
              Buscar Notícias
            </h2>
            <form @submit.prevent="searchNews" class="flex gap-4">
              <input
                v-model="searchForm.title"
                type="text"
                placeholder="Digite o que você quer buscar..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                required
              >
              <button
                type="submit"
                :disabled="searchForm.processing"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
              >
                <span v-if="searchForm.processing">Buscando...</span>
                <span v-else>Buscar</span>
              </button>
            </form>
          </div>
        </div>

        <!-- Categories -->
        <div class="mb-8">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Categorias</h3>
            <div class="flex flex-wrap gap-2">
              <Link
                v-for="(label, key) in categories"
                :key="key"
                :href="`/news/category/${key}`"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                :class="{ 'bg-blue-100 text-blue-700': currentCategory === key }"
              >
                {{ label }}
              </Link>
            </div>
          </div>
        </div>

        <!-- Headlines Section -->
        <div v-if="headlines && headlines.success && headlines.articles && headlines.articles.length > 0" class="mb-8">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Destaques</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <article 
                v-for="article in headlines.articles.slice(0, 6)" 
                :key="article.url"
                class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
              >
                <img 
                  v-if="article.urlToImage" 
                  :src="article.urlToImage" 
                  :alt="article.title"
                  class="w-full h-48 object-cover"
                  @error="handleImageError"
                >
                <div class="p-4">
                  <h4 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                    {{ article.title }}
                  </h4>
                  <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                    {{ article.description }}
                  </p>
                  <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                    <span>{{ formatDate(article.publishedAt) }}</span>
                    <span>{{ article.source?.name }}</span>
                  </div>
                  <a 
                    :href="article.url" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
                  >
                    Ler mais
                  </a>
                </div>
              </article>
            </div>
          </div>
        </div>

        <!-- Category News Section -->
        <div v-if="categoryNews && categoryNews.success && categoryNews.articles && categoryNews.articles.length > 0" class="mb-8">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">
              {{ categories[currentCategory] || 'Notícias' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <article 
                v-for="article in categoryNews.articles" 
                :key="article.url"
                class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
              >
                <img 
                  v-if="article.urlToImage" 
                  :src="article.urlToImage" 
                  :alt="article.title"
                  class="w-full h-48 object-cover"
                  @error="handleImageError"
                >
                <div class="p-4">
                  <h4 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                    {{ article.title }}
                  </h4>
                  <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                    {{ article.description }}
                  </p>
                  <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                    <span>{{ formatDate(article.publishedAt) }}</span>
                    <span>{{ article.source?.name }}</span>
                  </div>
                  <a 
                    :href="article.url" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
                  >
                    Ler mais
                  </a>
                </div>
              </article>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="!headlines && !categoryNews" class="mb-8">
          <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Carregando notícias...</p>
          </div>
        </div>

        <!-- No Results Message -->
        <div v-if="(headlines && headlines.success && (!headlines.articles || headlines.articles.length === 0)) || (categoryNews && categoryNews.success && (!categoryNews.articles || categoryNews.articles.length === 0))" class="mb-8">
          <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma notícia encontrada</h3>
            <p class="text-gray-600">Tente buscar por outros termos ou verificar outras categorias.</p>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="(headlines && headlines.error) || (categoryNews && categoryNews.error)" class="mb-8">
          <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-red-700">
              {{ (headlines && headlines.error) || (categoryNews && categoryNews.error) }}
            </p>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="categoryNews && categoryNews.totalPages && categoryNews.totalPages > 1" class="flex justify-center">
          <div class="bg-white rounded-lg shadow-lg px-4 py-3">
            <nav class="flex space-x-2">
              <Link
                v-if="currentPage > 1"
                :href="`/news?page=${currentPage - 1}&category=${currentCategory}`"
                class="px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded"
              >
                Anterior
              </Link>
              
              <span 
                v-for="page in getPageNumbers()" 
                :key="page"
                class="px-3 py-2 rounded"
                :class="page === currentPage ? 'bg-blue-600 text-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
              >
                <Link v-if="page !== currentPage" :href="`/news?page=${page}&category=${currentCategory}`">
                  {{ page }}
                </Link>
                <span v-else>{{ page }}</span>
              </span>
              
              <Link
                v-if="currentPage < (categoryNews.totalPages || 1)"
                :href="`/news?page=${currentPage + 1}&category=${currentCategory}`"
                class="px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded"
              >
                Próxima
              </Link>
            </nav>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  headlines: {
    type: Object,
    default: () => ({})
  },
  categoryNews: {
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
    default: () => ({})
  }
})

const searchForm = useForm({
  title: ''
})

const searchNews = () => {
  searchForm.post('/news/search')
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
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
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>