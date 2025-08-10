<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 p-4">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Histórico de Buscas</h1>
        <ul class="list-disc">
          <li v-for="search in searches.data" :key="search.id" class="mb-2">
            <a :href="`/news?query=${search.query}`" class="text-blue-500">{{ search.query }} ({{ search.created_at }})</a>
          </li>
        </ul>
        <!-- Paginação simples para histórico -->
        <div class="mt-4">
          <button v-if="searches.prev_page_url" @click="loadPage(searches.prev_page_url)" class="mx-2 bg-gray-300 p-2 rounded">Anterior</button>
          <button v-if="searches.next_page_url" @click="loadPage(searches.next_page_url)" class="mx-2 bg-blue-500 text-white p-2 rounded">Próxima</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { router } from '@inertiajs/vue3';
  
  const props = defineProps({ searches: Object });
  const searches = ref(props.searches);
  
  const loadPage = (url) => {
    router.get(url);
  };
  </script>