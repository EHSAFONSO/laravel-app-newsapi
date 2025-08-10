<template>
  <div style="padding: 20px; font-family: Arial, sans-serif;">
    <h1 style="color: #333;">Página Home</h1>
    <p style="color: #666;">{{ message || 'Inertia está funcionando!' }}</p>
    <div style="margin-top: 20px;">
      <a href="/news" style="color: #007bff; text-decoration: none;">Ir para News</a>
    </div>
  </div>
</template>

<script setup>
defineProps({
  message: {
    type: String,
    default: ''
  }
})
</script>
