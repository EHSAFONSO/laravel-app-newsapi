<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-4">
            <h1 class="text-2xl font-bold text-gray-900">Histórico de Buscas</h1>
          </div>
          <nav class="flex space-x-4">
            <a 
              href="/news" 
              class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              Notícias
            </a>
            <a 
              href="/history" 
              class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors"
              :class="{ 'bg-gray-100': $page.url === '/history' }"
            >
              Histórico
            </a>
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Debug Info -->
      <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <h3 class="text-sm font-medium text-yellow-800 mb-2">Debug Info:</h3>
        <p class="text-sm text-yellow-700">
          searchHistory length: {{ searchHistory ? searchHistory.length : 'undefined' }}
        </p>
        <p class="text-sm text-yellow-700">
          searchHistory data: {{ JSON.stringify(searchHistory) }}
        </p>
      </div>

      <!-- Search Form -->
      <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
        <form @submit.prevent="searchNews" class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
              Buscar notícias
            </label>
            <input
              id="search"
              v-model="searchTerm"
              type="text"
              placeholder="Digite o título da notícia..."
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </div>
          <div class="flex items-end">
            <button
              type="submit"
              class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
              :disabled="isSearching"
            >
              <span v-if="isSearching">Buscando...</span>
              <span v-else>Buscar</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Search Results -->
      <div v-if="searchResults && searchResults.articles && searchResults.articles.length > 0" class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
          <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">
              Resultados para "{{ searchTerm }}"
            </h2>
            <p class="text-sm text-gray-600 mt-1">
              {{ searchResults.totalResults }} notícias encontradas
            </p>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            <div 
              v-for="article in searchResults.articles" 
              :key="article.url"
              class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
            >
              <img 
                :src="article.urlToImage || 'https://via.placeholder.com/400x200/3B82F6/FFFFFF?text=Notícia'" 
                :alt="article.title"
                class="w-full h-48 object-cover"
              />
              <div class="p-4">
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                  {{ article.title }}
                </h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                  {{ article.description }}
                </p>
                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                  <span>{{ article.source?.name || 'Fonte desconhecida' }}</span>
                  <span>{{ formatDate(article.publishedAt) }}</span>
                </div>
                <a 
                  :href="article.url" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors"
                >
                  Ler mais
                </a>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="searchResults.totalPages > 1" class="px-6 py-4 border-t bg-gray-50">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-700">
                Página {{ searchResults.currentPage }} de {{ searchResults.totalPages }}
              </div>
              <div class="flex space-x-2">
                <button
                  v-if="searchResults.currentPage > 1"
                  @click="changePage(searchResults.currentPage - 1)"
                  class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 transition-colors"
                >
                  Anterior
                </button>
                <button
                  v-if="searchResults.currentPage < searchResults.totalPages"
                  @click="changePage(searchResults.currentPage + 1)"
                  class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 transition-colors"
                >
                  Próxima
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- No Results Message -->
      <div v-else-if="searchResults && searchResults.articles && searchResults.articles.length === 0" class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma notícia encontrada</h3>
          <p class="mt-1 text-sm text-gray-500">
            Tente usar termos diferentes para sua busca.
          </p>
        </div>
      </div>

      <!-- Search History -->
      <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b">
          <h2 class="text-lg font-semibold text-gray-900">Histórico de Buscas</h2>
          <p class="text-sm text-gray-600 mt-1">
            Suas buscas recentes
          </p>
        </div>
        
        <div v-if="searchHistory && searchHistory.length > 0" class="divide-y divide-gray-200">
          <div 
            v-for="search in searchHistory" 
            :key="search.id"
            class="px-6 py-4 hover:bg-gray-50 transition-colors"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ search.title }}</p>
                  <p class="text-xs text-gray-500">{{ formatDate(search.created_at) }}</p>
                </div>
              </div>
              <button
                @click="repeatSearch(search.title)"
                class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors"
              >
                Buscar novamente
              </button>
            </div>
          </div>
        </div>
        
        <div v-else class="px-6 py-8 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma busca realizada</h3>
          <p class="mt-1 text-sm text-gray-500">
            Faça sua primeira busca para ver o histórico aqui.
          </p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  searchHistory: {
    type: Array,
    default: () => []
  }
})

const searchTerm = ref('')
const searchResults = ref(null)
const isSearching = ref(false)

const searchNews = async () => {
  if (!searchTerm.value.trim()) return
  
  isSearching.value = true
  
  try {
    const response = await fetch('/news/search', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({ title: searchTerm.value })
    })
    
    if (response.ok) {
      const data = await response.json()
      searchResults.value = data
    }
  } catch (error) {
    console.error('Erro na busca:', error)
  } finally {
    isSearching.value = false
  }
}

const repeatSearch = (title) => {
  searchTerm.value = title
  searchNews()
}

const changePage = (page) => {
  if (!searchTerm.value) return
  
  router.post('/news/search', { 
    title: searchTerm.value,
    page: page 
  })
}

const formatDate = (dateString) => {
  if (!dateString) return 'Data não disponível'
  
  const date = new Date(dateString)
  return date.toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  // Focus on search input when component mounts
  const searchInput = document.getElementById('search')
  if (searchInput) {
    searchInput.focus()
  }
})
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