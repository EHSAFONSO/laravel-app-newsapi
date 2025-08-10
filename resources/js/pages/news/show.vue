<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-4">
            <a 
              href="/news" 
              class="text-gray-600 hover:text-gray-900 transition-colors"
            >
              ← Voltar para Notícias
            </a>
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
            >
              Histórico
            </a>
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <article class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Article Image -->
        <div class="aspect-w-16 aspect-h-9">
          <img 
            :src="news.image_url || 'https://via.placeholder.com/800x400/3B82F6/FFFFFF?text=Notícia'" 
            :alt="news.title"
            class="w-full h-64 object-cover"
          />
        </div>

        <!-- Article Content -->
        <div class="p-6">
          <!-- Meta Information -->
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4 text-sm text-gray-500">
              <span>{{ formatDate(news.created_at) }}</span>
              <span>•</span>
              <span>{{ news.source || 'Portal de Notícias' }}</span>
            </div>
            <div class="flex space-x-2">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ news.category || 'Geral' }}
              </span>
            </div>
          </div>

          <!-- Title -->
          <h1 class="text-3xl font-bold text-gray-900 mb-4">
            {{ news.title }}
          </h1>

          <!-- Description -->
          <div class="prose prose-lg max-w-none mb-6">
            <p class="text-gray-700 leading-relaxed">
              {{ news.description }}
            </p>
          </div>

          <!-- Content -->
          <div class="prose prose-lg max-w-none mb-6">
            <p class="text-gray-700 leading-relaxed">
              {{ news.content }}
            </p>
          </div>

          <!-- External Link -->
          <div class="border-t pt-6">
            <a 
              :href="news.url || '#'" 
              target="_blank" 
              rel="noopener noreferrer"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              Ler notícia completa
              <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>
      </article>

      <!-- Back Button -->
      <div class="mt-8">
        <a 
          href="/news" 
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
        >
          ← Voltar para Notícias
        </a>
      </div>
    </main>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  news: {
    type: Object,
    required: true
  }
})

const formatDate = (dateString) => {
  if (!dateString) return 'Data não disponível'
  
  const date = new Date(dateString)
  return date.toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<style scoped>
.prose {
  color: #374151;
}

.prose p {
  margin-bottom: 1rem;
}

.prose-lg {
  font-size: 1.125rem;
  line-height: 1.75;
}
</style>
