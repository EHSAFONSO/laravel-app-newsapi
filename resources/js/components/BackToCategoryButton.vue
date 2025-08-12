<template>
  <div v-if="showButton" class="flex space-x-4">
    <!-- Botão Voltar para Categoria -->
    <a 
      :href="`/news/category/${category}`"
      class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors font-medium"
    >
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
      </svg>
      Voltar para {{ categoryLabel }}
    </a>
    
    <!-- Botão Voltar ao Início -->
    <a 
      href="/news" 
      class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-full hover:bg-gray-50 transition-colors font-medium"
    >
      Voltar ao início
    </a>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  category: {
    type: String,
    default: null
  },
  categoryLabel: {
    type: String,
    default: ''
  }
})

const showButton = computed(() => {
  return props.category && props.category !== 'general'
})
</script>
