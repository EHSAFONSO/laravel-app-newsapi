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
            <a href="/history" class="relative px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors group">
              Histórico
              <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
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
              <a href="/history" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                Histórico
              </a>
            </nav>
            <div class="mt-4 pt-4 border-t border-gray-200/50">
              <a href="/" class="text-gray-600 px-3 py-2 text-sm font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors block">
                Tela Inicial
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Hero Section -->
      <div class="text-center mb-16 fade-in-up">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
          Teste do 
          <span class="gradient-text">Sistema</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Página de teste para verificar o funcionamento do Vue.js, Tailwind CSS e Inertia.js
        </p>
      </div>
      
      <!-- Teste de Grid com Design Moderno -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 news-grid">
        <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Card 1</h3>
          <p class="text-gray-600">Este é um teste usando Tailwind CSS para verificar se o design está funcionando corretamente.</p>
        </div>
        <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Card 2</h3>
          <p class="text-gray-600">Este é um teste usando Tailwind CSS para verificar se o design está funcionando corretamente.</p>
        </div>
        <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Card 3</h3>
          <p class="text-gray-600">Este é um teste usando Tailwind CSS para verificar se o design está funcionando corretamente.</p>
        </div>
      </div>
      
      <!-- Teste de Cores -->
      <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-lg mb-8 fade-in-up" style="animation-delay: 0.2s;">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Teste de Cores</h3>
        <div class="flex gap-4">
          <div class="w-16 h-16 bg-red-500 rounded-lg shadow-md"></div>
          <div class="w-16 h-16 bg-green-500 rounded-lg shadow-md"></div>
          <div class="w-16 h-16 bg-blue-500 rounded-lg shadow-md"></div>
          <div class="w-16 h-16 bg-yellow-500 rounded-lg shadow-md"></div>
          <div class="w-16 h-16 bg-purple-500 rounded-lg shadow-md"></div>
        </div>
      </div>
      
      <!-- Botões -->
      <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-lg mb-8 fade-in-up" style="animation-delay: 0.3s;">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Teste de Botões</h3>
        <div class="flex gap-4 flex-wrap">
          <button class="btn-primary hover-scale" @click="testClick">
            Botão Azul
          </button>
          <button class="btn-success hover-scale" @click="testClick">
            Botão Verde
          </button>
          <button class="btn-error hover-scale" @click="testClick">
            Botão Vermelho
          </button>
        </div>
      </div>
      
      <!-- Status -->
      <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-lg mb-8 fade-in-up" style="animation-delay: 0.4s;">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Status do Sistema</h3>
        <div class="space-y-2 text-gray-600">
          <p class="flex items-center">
            <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
            Vue.js: {{ vueWorking ? '✅ Funcionando' : '❌ Não funcionando' }}
          </p>
          <p class="flex items-center">
            <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
            CSS Inline: ✅ Funcionando
          </p>
          <p class="flex items-center">
            <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
            Inertia.js: ✅ Funcionando (página carregou)
          </p>
          <p class="flex items-center">
            <span class="w-2 h-2 rounded-full mr-3" :class="tailwindWorking ? 'bg-green-500' : 'bg-red-500'"></span>
            Tailwind CSS: {{ tailwindWorking ? '✅ Funcionando' : '❌ Não funcionando' }}
          </p>
        </div>
      </div>
      
      <!-- Links -->
      <div class="text-center fade-in-up" style="animation-delay: 0.5s;">
        <a href="/news" class="btn-primary hover-scale">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
          </svg>
          Ir para Notícias
        </a>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const vueWorking = ref(false)
const tailwindWorking = ref(false)
const mobileMenuOpen = ref(false)

onMounted(() => {
  vueWorking.value = true
  
  // Teste simples para verificar se o Tailwind está funcionando
  const testElement = document.createElement('div')
  testElement.className = 'bg-red-500 w-4 h-4'
  document.body.appendChild(testElement)
  
  const computedStyle = window.getComputedStyle(testElement)
  tailwindWorking.value = computedStyle.backgroundColor !== 'rgba(0, 0, 0, 0)' && 
                          computedStyle.backgroundColor !== 'transparent'
  
  document.body.removeChild(testElement)
})

const testClick = () => {
  alert('Vue.js está funcionando!')
}
</script>
