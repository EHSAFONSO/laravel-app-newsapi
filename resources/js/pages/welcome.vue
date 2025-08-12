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
            <a href="/" class="relative px-4 py-2 text-sm font-medium text-blue-600 transition-colors group">
              Início
              <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600"></span>
            </a>
            <a href="/news" class="relative px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors group">
              Notícias
              <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="/history" class="relative px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors group">
              Histórico
              <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
          </nav>
          
          <!-- Desktop Actions -->
          <div class="hidden md:flex items-center space-x-3">
            <ThemeToggle />
            <button 
              @click="toggleAccessibility"
              class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 dark:text-gray-300 dark:hover:text-blue-400 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200 focus-ring"
              title="Menu de Acessibilidade"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </button>
          </div>
          
          <!-- Mobile Menu Button -->
          <div class="md:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 focus-ring">
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
              <a href="/" class="text-blue-600 px-3 py-2 text-sm font-medium border-l-4 border-blue-600 bg-blue-50 rounded-r-lg">
                Início
              </a>
              <a href="/news" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                Notícias
              </a>
              <a href="/history" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                Histórico
              </a>
            </nav>
            <div class="mt-4 pt-4 border-t border-gray-200/50 dark:border-gray-700/50">
              <div class="flex items-center justify-between px-3 py-2">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Tema</span>
                <ThemeToggle />
              </div>
              <button 
                @click="toggleAccessibility"
                class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 dark:text-gray-300 dark:hover:text-blue-400 dark:hover:bg-blue-900/20 rounded-lg transition-colors w-full mt-2"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Acessibilidade
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main style="max-width: 80rem; margin: 0 auto; padding: 1.5rem 1rem;">
      
      <!-- Hero Section Moderna -->
      <div class="text-center mb-16 fade-in-up">
        <div class="max-w-4xl mx-auto">
          <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
            Bem-vindo ao 
            <span class="gradient-text">Portal de Notícias</span>
          </h2>
          <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
            Descubra as últimas notícias do Brasil e do mundo. Mantenha-se informado com conteúdo de qualidade e fontes confiáveis.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="/news" class="btn-primary hover-scale">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
              </svg>
              Ver Notícias
            </a>
            <a href="/history" class="btn-secondary hover-scale">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Histórico de Buscas
            </a>
          </div>
        </div>
      </div>

      <!-- Search Bar Moderna -->
      <div class="mb-12 fade-in-up" style="animation-delay: 0.2s;">
        <div class="max-w-2xl mx-auto">
          <form @submit.prevent="searchNews" class="relative">
            <div class="flex items-center bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl px-6 py-4 shadow-lg hover:shadow-xl transition-all duration-300 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20">
              <svg class="w-6 h-6 text-gray-400 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input
                v-model="searchForm.title"
                type="text"
                placeholder="Buscar notícias..."
                class="flex-1 text-lg border-none outline-none bg-transparent min-w-0 placeholder-gray-400 dark:text-white dark:placeholder-gray-500"
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

      <!-- Categories Section -->
      <div class="mb-16 fade-in-up" style="animation-delay: 0.3s;">
        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">
          Explore por <span class="gradient-text">Categoria</span>
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
          <a
            v-for="(label, key) in categories"
            :key="key"
            :href="key === 'general' ? '/news' : `/news/category/${key}`"
            class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl p-4 text-center hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-400 transition-all duration-300 hover:scale-105"
          >
            <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:from-blue-600 group-hover:to-purple-700 transition-all duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
              </svg>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ label }}</span>
          </a>
        </div>
      </div>

      <!-- Featured News Section -->
      <div class="mb-16 fade-in-up" style="animation-delay: 0.4s;">
        <h3 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-8 text-center">
          <span class="gradient-text">Destaques</span> do Dia
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 news-grid">
          <article 
            v-for="(article, index) in featuredNews" 
            :key="`featured-${index}`"
            class="news-card group"
          >
            <!-- Badge de categoria -->
            <div class="absolute top-4 left-4 z-10">
              <span class="badge badge-primary">
                {{ article.category || getCategoryFromTitle(article.title) }}
              </span>
            </div>
            
            <!-- Imagem com overlay -->
            <div class="relative h-48 overflow-hidden">
              <img 
                v-if="article.image" 
                :src="article.image" 
                :alt="article.title"
                :data-image-key="`featured-${index}`"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                @error="handleImageError"
              >
              <div v-if="!article.image || imageErrors[`featured-${index}`]" class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent flex items-center justify-center" :style="getPlaceholderStyle(article)">
                <div class="text-center text-white font-semibold text-lg px-4">
                  {{ article.category || getCategoryFromTitle(article.title) }}
                </div>
              </div>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-6">
              <div class="flex items-center gap-2 mb-3">
                <span class="text-xs text-gray-500 font-medium">{{ article.source }}</span>
                <span class="text-gray-300">•</span>
                <span class="text-xs text-gray-500">{{ article.date }}</span>
              </div>
              <h4 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors mb-3">
                <a :href="`/news/${article.id}`" class="text-inherit no-underline">
                  {{ article.title }}
                </a>
              </h4>
              <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                {{ article.description }}
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
      </div>

      <!-- Quick Stats -->
      <div class="mb-16 fade-in-up" style="animation-delay: 0.5s;">
        <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl p-8 shadow-lg">
          <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 text-center">
            <span class="gradient-text">Estatísticas</span> da Plataforma
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center group">
              <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
              </div>
              <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">7</div>
              <div class="text-sm text-gray-600 font-medium">Categorias</div>
            </div>
            <div class="text-center group">
              <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div class="text-3xl md:text-4xl font-bold text-green-600 mb-2">24</div>
              <div class="text-sm text-gray-600 font-medium">Notícias por Página</div>
            </div>
            <div class="text-center group">
              <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <div class="text-3xl md:text-4xl font-bold text-purple-600 mb-2">∞</div>
              <div class="text-sm text-gray-600 font-medium">Fontes Confiáveis</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div style="text-align: center; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 0.5rem; padding: 2rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Pronto para se manter informado?</h3>
        <p style="font-size: 1.125rem; margin-bottom: 1.5rem; opacity: 0.9;">
          Acesse nossa seção de notícias e descubra as últimas atualizações do Brasil e do mundo.
        </p>
        <a href="/news" style="display: inline-block; padding: 0.75rem 1.5rem; background: white; color: #2563eb; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
          Explorar Notícias
        </a>
      </div>
    </main>

    <!-- Footer with Copyright -->
    <footer style="background: #1e293b; color: white; padding: 2rem 1rem; margin-top: 3rem;">
      <div style="max-width: 80rem; margin: 0 auto; text-align: center;">
        <div style="margin-bottom: 1rem;">
          <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Portal de Notícias</h4>
          <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;">
            Mantenha-se informado com as últimas notícias do Brasil e do mundo
          </p>
        </div>
        
        <div style="border-top: 1px solid #334155; padding-top: 1rem;">
          <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 0.5rem;">
            © 2025 Portal de Notícias. Todos os direitos reservados.
          </p>
          <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 0.5rem;">
            Desenvolvido por 
            <a href="https://www.linkedin.com/in/ehsafonso/" target="_blank" rel="noopener noreferrer" style="color: #60a5fa; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#93c5fd'" onmouseout="this.style.color='#60a5fa'">
              Eduardo Henrique Dos Santos Afonso
            </a>
          </p>
          <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1rem;">
            <a href="https://www.linkedin.com/in/ehsafonso/" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #0ea5e9; color: white; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0284c7'" onmouseout="this.style.backgroundColor='#0ea5e9'">
              <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
              LinkedIn
            </a>
          </div>
    </div>
      </div>
    </footer>
    
    <!-- Menu de Acessibilidade -->
    <AccessibilityMenu />
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, computed, reactive } from 'vue'
import AccessibilityMenu from '../components/AccessibilityMenu.vue'
import ThemeToggle from '../components/ThemeToggle.vue'

const searchForm = useForm({
  title: ''
})

const mobileMenuOpen = ref(false)
const imageErrors = reactive({})

const categories = ref({
  'general': 'Geral',
  'business': 'Negócios',
  'technology': 'Tecnologia',
  'sports': 'Esportes',
  'entertainment': 'Entretenimento',
  'health': 'Saúde',
  'science': 'Ciência'
})

const props = defineProps({
  techNews: {
    type: Object,
    default: () => ({})
  },
  businessNews: {
    type: Object,
    default: () => ({})
  },
  healthNews: {
    type: Object,
    default: () => ({})
  },
  headlines: {
    type: Object,
    default: () => ({})
  }
})

const featuredNews = computed(() => {
  const news = []
  
  // Adicionar notícia de tecnologia
  if (props.techNews?.success && props.techNews.articles?.length > 0) {
    const article = props.techNews.articles[0]
    news.push({
      id: article.id,
      title: article.title,
      description: article.description,
      source: article.source?.name || 'Tech News',
      date: formatDate(article.publishedAt),
      image: article.urlToImage,
      url: article.url,
      category: 'Tecnologia'
    })
  } else {
    // Fallback para dados de exemplo
    news.push({
      id: null,
      title: 'Tecnologia: Novos avanços em IA revolucionam o mercado',
      description: 'Empresas de tecnologia anunciam descobertas significativas em inteligência artificial que podem transformar diversos setores.',
      source: 'Tech News',
      date: '09/08/2025',
      image: null,
      url: '/news/category/technology',
      category: 'Tecnologia'
    })
  }
  
  // Adicionar notícia de economia
  if (props.businessNews?.success && props.businessNews.articles?.length > 0) {
    const article = props.businessNews.articles[0]
    news.push({
      id: article.id,
      title: article.title,
      description: article.description,
      source: article.source?.name || 'Economia Hoje',
      date: formatDate(article.publishedAt),
      image: article.urlToImage,
      url: article.url,
      category: 'Economia'
    })
  } else {
    // Fallback para dados de exemplo
    news.push({
      id: null,
      title: 'Economia: Mercado brasileiro mostra sinais de recuperação',
      description: 'Indicadores econômicos apontam para uma recuperação gradual do mercado brasileiro nos próximos meses.',
      source: 'Economia Hoje',
      date: '09/08/2025',
      image: null,
      url: '/news/category/business',
      category: 'Economia'
    })
  }
  
  // Adicionar notícia de saúde
  if (props.healthNews?.success && props.healthNews.articles?.length > 0) {
    const article = props.healthNews.articles[0]
    news.push({
      id: article.id,
      title: article.title,
      description: article.description,
      source: article.source?.name || 'Saúde Brasil',
      date: formatDate(article.publishedAt),
      image: article.urlToImage,
      url: article.url,
      category: 'Saúde'
    })
  } else {
    // Fallback para dados de exemplo
    news.push({
      id: null,
      title: 'Saúde: Novas diretrizes para vacinação são anunciadas',
      description: 'Ministério da Saúde divulga novas orientações para campanhas de vacinação em todo o país.',
      source: 'Saúde Brasil',
      date: '09/08/2025',
      image: null,
      url: '/news/category/health',
      category: 'Saúde'
    })
  }
  
  return news
})

const searchNews = () => {
  if (!searchForm.title.trim()) return
  window.location.href = `/news/search?title=${encodeURIComponent(searchForm.title)}`
}

const toggleAccessibility = () => {
  // Esta função será chamada pelo componente AccessibilityMenu
  // O componente gerencia seu próprio estado
}

const handleImageError = (event) => {
  const imageKey = event.target.dataset.imageKey
  if (imageKey) {
    imageErrors[imageKey] = true
  }
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
