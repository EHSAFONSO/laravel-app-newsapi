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
      
      <!-- Article -->
      <article v-if="article" class="mb-16">
        <!-- Article Header -->
        <header class="mb-8">
          <div class="flex items-center space-x-2 mb-4">
            <span class="text-sm text-gray-500">{{ article.source?.name }}</span>
            <span class="text-gray-300">•</span>
            <span class="text-sm text-gray-500">{{ formatDate(article.publishedAt) }}</span>
          </div>
          
          <h1 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">
            {{ article.title }}
          </h1>
          
          <p class="text-xl text-gray-600 leading-relaxed mb-8">
            {{ article.description }}
          </p>
          
          <div class="flex items-center space-x-4">
            <a 
              :href="article.url" 
              target="_blank" 
              rel="noopener noreferrer"
              class="inline-flex items-center px-6 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-colors font-medium"
            >
              Ler artigo completo →
            </a>
            <a 
              href="/news" 
              class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-full hover:bg-gray-50 transition-colors font-medium"
            >
              Voltar ao início
            </a>
          </div>
        </header>

        <!-- Article Image -->
        <div v-if="article.urlToImage" class="mb-8">
          <img 
            :src="article.urlToImage" 
            :alt="article.title"
            class="w-full h-96 object-cover rounded-lg"
            @error="handleImageError"
          >
        </div>

        <!-- Article Content -->
        <div class="prose prose-lg max-w-none">
          <div v-if="article.content" class="text-gray-700 leading-relaxed">
            <p>{{ article.content }}</p>
          </div>
          
          <div v-else class="text-gray-600 italic">
            <p>Conteúdo completo disponível no link original.</p>
          </div>
        </div>

        <!-- Article Footer -->
        <footer class="mt-12 pt-8 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <span class="text-sm text-gray-500">Fonte: {{ article.source?.name }}</span>
              <span class="text-gray-300">•</span>
              <span class="text-sm text-gray-500">{{ formatDate(article.publishedAt) }}</span>
            </div>
            
            <div class="flex space-x-4">
              <a 
                :href="article.url" 
                target="_blank" 
                rel="noopener noreferrer"
                class="text-sm font-medium text-gray-900 hover:text-gray-700 transition-colors"
              >
                Ver original
              </a>
            </div>
          </div>
        </footer>
      </article>

      <!-- Loading State -->
      <div v-if="!article" class="text-center py-16">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-black mx-auto mb-4"></div>
        <p class="text-gray-600">Carregando notícia...</p>
      </div>

      <!-- Error State -->
      <div v-if="error" class="text-center py-16">
        <div class="max-w-md mx-auto">
          <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Erro ao carregar notícia</h3>
          <p class="text-gray-600 mb-6">
            {{ error }}
          </p>
          <a 
            href="/news" 
            class="inline-block px-6 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-colors font-medium"
          >
            Voltar ao início
          </a>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
const props = defineProps({
  article: {
    type: Object,
    default: null
  },
  error: {
    type: String,
    default: null
  }
})

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
</script>

<style scoped>
.prose {
  color: #374151;
}

.prose p {
  margin-bottom: 1.5rem;
  line-height: 1.75;
}

.prose-lg {
  font-size: 1.125rem;
}
</style>
