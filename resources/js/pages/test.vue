<template>
  <div style="min-height: 100vh; background-color: #f0f9ff; padding: 2rem;">
    <div style="max-width: 64rem; margin: 0 auto;">
      <h1 style="font-size: 2.5rem; font-weight: bold; color: #1e40af; margin-bottom: 2rem; text-align: center;">
        Teste Simples - CSS Inline
      </h1>
      
      <!-- Teste de Grid com CSS Inline -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 1.5rem;">
          <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Card 1</h3>
          <p style="color: #6b7280;">Este é um teste usando CSS inline para verificar se o problema é no Tailwind.</p>
        </div>
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 1.5rem;">
          <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Card 2</h3>
          <p style="color: #6b7280;">Este é um teste usando CSS inline para verificar se o problema é no Tailwind.</p>
        </div>
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 1.5rem;">
          <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Card 3</h3>
          <p style="color: #6b7280;">Este é um teste usando CSS inline para verificar se o problema é no Tailwind.</p>
        </div>
      </div>
      
      <!-- Teste de Cores -->
      <div style="background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Teste de Cores</h3>
        <div style="display: flex; gap: 1rem;">
          <div style="width: 4rem; height: 4rem; background: #ef4444; border-radius: 0.25rem;"></div>
          <div style="width: 4rem; height: 4rem; background: #10b981; border-radius: 0.25rem;"></div>
          <div style="width: 4rem; height: 4rem; background: #3b82f6; border-radius: 0.25rem;"></div>
          <div style="width: 4rem; height: 4rem; background: #f59e0b; border-radius: 0.25rem;"></div>
          <div style="width: 4rem; height: 4rem; background: #8b5cf6; border-radius: 0.25rem;"></div>
        </div>
      </div>
      
      <!-- Botões -->
      <div style="background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Teste de Botões</h3>
        <div style="display: flex; gap: 1rem;">
          <button style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border-radius: 0.25rem; border: none; cursor: pointer;" @click="testClick">
            Botão Azul
          </button>
          <button style="padding: 0.5rem 1rem; background: #10b981; color: white; border-radius: 0.25rem; border: none; cursor: pointer;" @click="testClick">
            Botão Verde
          </button>
          <button style="padding: 0.5rem 1rem; background: #ef4444; color: white; border-radius: 0.25rem; border: none; cursor: pointer;" @click="testClick">
            Botão Vermelho
          </button>
        </div>
      </div>
      
      <!-- Status -->
      <div style="background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Status</h3>
        <div style="color: #6b7280;">
          <p>Vue.js: {{ vueWorking ? '✅ Funcionando' : '❌ Não funcionando' }}</p>
          <p>CSS Inline: ✅ Funcionando</p>
          <p>Inertia.js: ✅ Funcionando (página carregou)</p>
          <p>Tailwind CSS: {{ tailwindWorking ? '✅ Funcionando' : '❌ Não funcionando' }}</p>
        </div>
      </div>
      
      <!-- Links -->
      <div style="text-align: center;">
        <a href="/news" style="display: inline-block; padding: 0.75rem 1.5rem; background: #3b82f6; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500;">
          Ir para Notícias
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const vueWorking = ref(false)
const tailwindWorking = ref(false)

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
