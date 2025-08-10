<template>
  <div style="padding: 20px; font-family: Arial, sans-serif;">
    <h1 style="color: #333;">Teste Simples do Inertia</h1>
    <p style="color: #666;">{{ message || 'Se você está vendo esta mensagem, o Inertia está funcionando corretamente!' }}</p>
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
