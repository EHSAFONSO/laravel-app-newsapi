<template>
  <div class="min-h-screen bg-gray-100 p-4">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Notícias</h1>
      
      <!-- Busca -->
      <form @submit.prevent="search" class="mb-8">
        <div class="flex">
          <input v-model="query" type="text" placeholder="Busque por título..." class="flex-1 p-2 border rounded-l" />
          <button type="submit" class="bg-blue-500 text-white p-2 rounded-r">Buscar</button>
        </div>
      </form>

      <!-- Erro -->
      <div v-if="error" class="text-red-500 mb-4">{{ error }}</div>

      <!-- Lista de Notícias -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="article in articles" :key="article.url" class="bg-white rounded shadow p-4">
          <img v-if="article.urlToImage" :src="article.urlToImage" alt="Imagem" class="w-full h-48 object-cover rounded mb-2" />
          <h2 class="text-xl font-bold mb-2">{{ article.title }}</h2>
          <p class="text-gray-600 mb-2">{{ article.description }}</p>
          <a :href="article.url" target="_blank" class="text-blue-500">Ler mais</a>
        </div>
      </div>

      <!-- Paginação -->
      <div class="mt-8 flex justify-center">
        <button v-if="currentPage > 1" @click="loadPage(currentPage - 1)" class="mx-2 bg-gray-300 p-2 rounded">Anterior</button>
        <button v-if="hasMore" @click="loadPage(currentPage + 1)" class="mx-2 bg-blue-500 text-white p-2 rounded">Próxima</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  articles: Array,
  totalResults: Number,
  currentPage: Number,
  query: String,
  error: String,
});

const query = ref(props.query || '');
const articles = ref(props.articles);
const currentPage = ref(props.currentPage);
const hasMore = ref((props.currentPage * 10) < props.totalResults);

const loadPage = (page) => {
  router.get('/news', { page, query: query.value }, { preserveState: true, replace: true });
};

const search = () => {
  loadPage(1);
};

watch(() => props.articles, (newArticles) => {
  articles.value = newArticles;
});
watch(() => props.currentPage, (newPage) => {
  currentPage.value = newPage;
});
watch(() => props.totalResults, (total) => {
  hasMore.value = (currentPage.value * 10) < total;
});
</script>