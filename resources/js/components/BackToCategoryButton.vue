<template>
  <div v-if="showButton" class="flex space-x-4">
    <!-- Botão Voltar para Categoria -->
    <a 
      :href="`/news/category/${category}`"
      class="btn-primary hover-scale"
    >
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
      </svg>
      Voltar para {{ categoryLabel }}
    </a>
    
    <!-- Botão Voltar ao Início -->
    <a 
      href="/news" 
      class="btn-secondary hover-scale"
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
