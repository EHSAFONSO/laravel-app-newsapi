<template>
  <div>
    <h1>Teste do Inertia</h1>
    <p>{{ message }}</p>
    <a href="/news">Ir para News</a>
  </div>
</template>

<script setup>
defineProps({
  message: String
})
</script>
