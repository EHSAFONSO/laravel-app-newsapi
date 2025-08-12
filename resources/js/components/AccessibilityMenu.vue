<template>
  <div class="accessibility-menu">
    <!-- Botão de Acessibilidade -->
    <button 
      @click="toggleMenu"
      class="fixed bottom-4 right-4 z-50 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 hover-scale"
      :aria-expanded="menuOpen"
      aria-label="Menu de Acessibilidade"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
    </button>
    
    <!-- Menu de Acessibilidade -->
    <div 
      v-if="menuOpen"
      class="fixed bottom-20 right-4 z-50 bg-white/80 backdrop-blur-sm rounded-xl shadow-xl border border-gray-200/50 p-4 min-w-64 fade-in-up"
    >
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Acessibilidade</h3>
      
      <!-- Contraste -->
      <div class="mb-4">
        <label class="flex items-center space-x-2">
          <input 
            type="checkbox" 
            v-model="highContrast"
            @change="toggleContrast"
            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
          >
          <span class="text-sm font-medium text-gray-700">Alto Contraste</span>
        </label>
      </div>
      
      <!-- Tamanho da Fonte -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Tamanho da Fonte</label>
        <div class="flex space-x-2">
          <button 
            @click="changeFontSize('small')"
            class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
          >
            A-
          </button>
          <button 
            @click="changeFontSize('normal')"
            class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors"
          >
            A
          </button>
          <button 
            @click="changeFontSize('large')"
            class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
          >
            A+
          </button>
        </div>
      </div>
      
      <!-- Espaçamento -->
      <div class="mb-4">
        <label class="flex items-center space-x-2">
          <input 
            type="checkbox" 
            v-model="increasedSpacing"
            @change="toggleSpacing"
            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
          >
          <span class="text-sm font-medium text-gray-700">Espaçamento Aumentado</span>
        </label>
      </div>
      
      <!-- Reduzir Animação -->
      <div class="mb-4">
        <label class="flex items-center space-x-2">
          <input 
            type="checkbox" 
            v-model="reduceMotion"
            @change="toggleMotion"
            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
          >
          <span class="text-sm font-medium text-gray-700">Reduzir Animação</span>
        </label>
      </div>
      
      <!-- Foco Visível -->
      <div class="mb-4">
        <label class="flex items-center space-x-2">
          <input 
            type="checkbox" 
            v-model="visibleFocus"
            @change="toggleFocus"
            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
          >
          <span class="text-sm font-medium text-gray-700">Foco Visível</span>
        </label>
      </div>
      
      <!-- Reset -->
      <button 
        @click="resetAccessibility"
        class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium"
      >
        Resetar Configurações
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const menuOpen = ref(false)
const highContrast = ref(false)
const increasedSpacing = ref(false)
const reduceMotion = ref(false)
const visibleFocus = ref(false)

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value
}

const toggleContrast = () => {
  if (highContrast.value) {
    document.documentElement.classList.add('high-contrast')
  } else {
    document.documentElement.classList.remove('high-contrast')
  }
  saveSettings()
}

const changeFontSize = (size) => {
  document.documentElement.classList.remove('font-small', 'font-normal', 'font-large')
  document.documentElement.classList.add(`font-${size}`)
  saveSettings()
}

const toggleSpacing = () => {
  if (increasedSpacing.value) {
    document.documentElement.classList.add('increased-spacing')
  } else {
    document.documentElement.classList.remove('increased-spacing')
  }
  saveSettings()
}

const toggleMotion = () => {
  if (reduceMotion.value) {
    document.documentElement.classList.add('reduce-motion')
  } else {
    document.documentElement.classList.remove('reduce-motion')
  }
  saveSettings()
}

const toggleFocus = () => {
  if (visibleFocus.value) {
    document.documentElement.classList.add('visible-focus')
  } else {
    document.documentElement.classList.remove('visible-focus')
  }
  saveSettings()
}

const resetAccessibility = () => {
  highContrast.value = false
  increasedSpacing.value = false
  reduceMotion.value = false
  visibleFocus.value = false
  
  document.documentElement.classList.remove(
    'high-contrast',
    'font-small',
    'font-normal',
    'font-large',
    'increased-spacing',
    'reduce-motion',
    'visible-focus'
  )
  
  localStorage.removeItem('accessibility-settings')
}

const saveSettings = () => {
  const settings = {
    highContrast: highContrast.value,
    increasedSpacing: increasedSpacing.value,
    reduceMotion: reduceMotion.value,
    visibleFocus: visibleFocus.value
  }
  localStorage.setItem('accessibility-settings', JSON.stringify(settings))
}

const loadSettings = () => {
  const saved = localStorage.getItem('accessibility-settings')
  if (saved) {
    const settings = JSON.parse(saved)
    highContrast.value = settings.highContrast || false
    increasedSpacing.value = settings.increasedSpacing || false
    reduceMotion.value = settings.reduceMotion || false
    visibleFocus.value = settings.visibleFocus || false
    
    // Aplicar configurações
    if (highContrast.value) document.documentElement.classList.add('high-contrast')
    if (increasedSpacing.value) document.documentElement.classList.add('increased-spacing')
    if (reduceMotion.value) document.documentElement.classList.add('reduce-motion')
    if (visibleFocus.value) document.documentElement.classList.add('visible-focus')
  }
}

onMounted(() => {
  loadSettings()
})
</script>

<style scoped>
/* Estilos de acessibilidade */
:global(.high-contrast) {
  --tw-bg-opacity: 1;
  background-color: rgb(0 0 0 / var(--tw-bg-opacity)) !important;
  color: rgb(255 255 255 / var(--tw-text-opacity)) !important;
}

:global(.high-contrast *) {
  background-color: rgb(0 0 0 / var(--tw-bg-opacity)) !important;
  color: rgb(255 255 255 / var(--tw-text-opacity)) !important;
  border-color: rgb(255 255 255 / var(--tw-border-opacity)) !important;
}

:global(.font-small) {
  font-size: 0.875rem !important;
}

:global(.font-large) {
  font-size: 1.25rem !important;
}

:global(.increased-spacing) * {
  line-height: 2 !important;
  letter-spacing: 0.1em !important;
}

:global(.reduce-motion) * {
  animation: none !important;
  transition: none !important;
}

:global(.visible-focus) *:focus {
  outline: 3px solid rgb(59 130 246) !important;
  outline-offset: 2px !important;
}
</style>
