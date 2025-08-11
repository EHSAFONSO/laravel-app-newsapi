<template>
  <div style="min-height: 100vh; background-color: #f8fafc;">
    <!-- Header Google News Style -->
    <header style="background: white; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 50;">
      <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0;">
          <div style="display: flex; align-items: center; gap: 2rem;">
            <h1 style="font-size: 1.5rem; font-weight: bold; color: #1e293b; margin: 0;">Portal de Notícias</h1>
            <nav style="display: flex; gap: 1.5rem;">
              <a href="/" style="color: #2563eb; padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 500; border-bottom: 2px solid #2563eb; text-decoration: none;">
                Início
              </a>
              <a href="/news" style="color: #64748b; padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
                Notícias
              </a>
              <a href="/history" style="color: #64748b; padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
                Histórico
              </a>
            </nav>
          </div>
          <a href="/history" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'">
            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Histórico
          </a>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main style="max-width: 80rem; margin: 0 auto; padding: 1.5rem 1rem;">
      
      <!-- Hero Section -->
      <div style="text-align: center; margin-bottom: 3rem;">
        <h2 style="font-size: 3rem; font-weight: bold; color: #1e293b; margin-bottom: 1rem;">Bem-vindo ao Portal de Notícias</h2>
        <p style="font-size: 1.25rem; color: #64748b; margin-bottom: 2rem; max-width: 48rem; margin-left: auto; margin-right: auto;">
          Descubra as últimas notícias do Brasil e do mundo. Mantenha-se informado com conteúdo de qualidade e fontes confiáveis.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/news" style="display: inline-block; padding: 0.75rem 1.5rem; background: #2563eb; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
            Ver Notícias
          </a>
          <a href="/history" style="display: inline-block; padding: 0.75rem 1.5rem; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 0.5rem; text-decoration: none; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'">
            Histórico de Buscas
          </a>
        </div>
      </div>

      <!-- Search Bar Google News Style -->
      <div style="margin-bottom: 2rem;">
        <div style="max-width: 32rem; margin: 0 auto;">
          <form @submit.prevent="searchNews" style="position: relative;">
            <div style="display: flex; align-items: center; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; transition: all 0.2s;" onmouseover="this.style.borderColor='#9ca3af'" onmouseout="this.style.borderColor='#d1d5db'">
              <svg style="width: 1.25rem; height: 1.25rem; color: #9ca3af; margin-right: 0.75rem; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input
                v-model="searchForm.title"
                type="text"
                placeholder="Buscar notícias..."
                style="flex: 1; font-size: 1rem; border: none; outline: none; background: transparent; min-width: 0;"
                required
              >
              <button
                type="submit"
                :disabled="searchForm.processing"
                style="margin-left: 0.75rem; padding: 0.5rem 1rem; background: #2563eb; color: white; border-radius: 0.375rem; border: none; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: background-color 0.2s; flex-shrink: 0;"
                onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'"
              >
                <span v-if="searchForm.processing">Buscando...</span>
                <span v-else>Buscar</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Categories Google News Style -->
      <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: bold; color: #1e293b; margin-bottom: 1rem;">Categorias</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
          <a
            v-for="(label, key) in categories"
            :key="key"
            :href="`/news/category/${key}`"
            style="padding: 0.5rem 1rem; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;"
            onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'"
          >
            {{ label }}
          </a>
        </div>
      </div>

      <!-- Featured News Section -->
      <div style="margin-bottom: 3rem;">
        <h3 style="font-size: 1.5rem; font-weight: bold; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center;">
          <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: #dc2626; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd" />
          </svg>
          Destaques
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
          <article 
            v-for="(article, index) in featuredNews" 
            :key="`featured-${index}`"
            style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden; transition: box-shadow 0.2s;"
            onmouseover="this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'" onmouseout="this.style.boxShadow='none'"
          >
            <!-- Imagem com Placeholder Estético -->
            <div style="position: relative; height: 12rem; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center;" :style="getPlaceholderStyle(article)">
              <div style="text-center text-white font-semibold text-lg px-4">
                {{ getCategoryFromTitle(article.title) }}
              </div>
            </div>
            
            <!-- Conteúdo -->
            <div style="padding: 1rem;">
              <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                <span style="font-size: 0.75rem; color: #6b7280; font-weight: 500;">{{ article.source }}</span>
                <span style="color: #d1d5db;">•</span>
                <span style="font-size: 0.75rem; color: #6b7280;">{{ article.date }}</span>
              </div>
              <h4 style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                <a :href="article.url" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#1e293b'">
                  {{ article.title }}
                </a>
              </h4>
              <p style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.75rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ article.description }}
              </p>
              <a 
                :href="article.url" 
                style="font-size: 0.75rem; color: #2563eb; font-weight: 500; text-decoration: none; transition: color 0.2s;"
                onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#2563eb'"
              >
                Ler mais →
              </a>
            </div>
          </article>
        </div>
      </div>

      <!-- Quick Stats -->
      <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: bold; color: #1e293b; margin-bottom: 1rem;">Estatísticas Rápidas</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
          <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 2rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">7</div>
            <div style="font-size: 0.875rem; color: #64748b;">Categorias</div>
          </div>
          <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 2rem; font-weight: bold; color: #10b981; margin-bottom: 0.5rem;">24</div>
            <div style="font-size: 0.875rem; color: #64748b;">Notícias por Página</div>
          </div>
          <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 2rem; font-weight: bold; color: #f59e0b; margin-bottom: 0.5rem;">∞</div>
            <div style="font-size: 0.875rem; color: #64748b;">Fontes Confiáveis</div>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div style="text-align: center; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 0.5rem; padding: 2rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Pronto para se manter informado?</h3>
        <p style="font-size: 1.125rem; margin-bottom: 1.5rem; opacity: 0.9;">
          Acesse nossa seção de notícias e descubra as últimas atualizações do Brasil e do mundo.
        </p>
        <a href="/news" style="display: inline-block; padding: 0.75rem 1.5rem; background: white; color: #2563eb; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
          Explorar Notícias
        </a>
      </div>
    </main>

    <!-- Footer with Copyright -->
    <footer style="background: #1e293b; color: white; padding: 2rem 1rem; margin-top: 3rem;">
      <div style="max-width: 80rem; margin: 0 auto; text-align: center;">
        <div style="margin-bottom: 1rem;">
          <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Portal de Notícias</h4>
          <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;">
            Mantenha-se informado com as últimas notícias do Brasil e do mundo
          </p>
        </div>
        
        <div style="border-top: 1px solid #334155; padding-top: 1rem;">
          <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 0.5rem;">
            © 2025 Portal de Notícias. Todos os direitos reservados.
          </p>
          <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 0.5rem;">
            Desenvolvido por 
            <a href="https://www.linkedin.com/in/ehsafonso/" target="_blank" rel="noopener noreferrer" style="color: #60a5fa; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#93c5fd'" onmouseout="this.style.color='#60a5fa'">
              Eduardo Henrique Dos Santos Afonso
            </a>
          </p>
          <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1rem;">
            <a href="https://www.linkedin.com/in/ehsafonso/" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #0ea5e9; color: white; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0284c7'" onmouseout="this.style.backgroundColor='#0ea5e9'">
              <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
              LinkedIn
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const searchForm = useForm({
  title: ''
})

const categories = ref({
  'general': 'Geral',
  'business': 'Negócios',
  'technology': 'Tecnologia',
  'sports': 'Esportes',
  'entertainment': 'Entretenimento',
  'health': 'Saúde',
  'science': 'Ciência'
})

const featuredNews = ref([
  {
    title: 'Tecnologia: Novos avanços em IA revolucionam o mercado',
    description: 'Empresas de tecnologia anunciam descobertas significativas em inteligência artificial que podem transformar diversos setores.',
    source: 'Tech News',
    date: '09/08/2025',
    image: null,
    url: '/news/category/technology'
  },
  {
    title: 'Economia: Mercado brasileiro mostra sinais de recuperação',
    description: 'Indicadores econômicos apontam para uma recuperação gradual do mercado brasileiro nos próximos meses.',
    source: 'Economia Hoje',
    date: '09/08/2025',
    image: null,
    url: '/news/category/business'
  },
  {
    title: 'Saúde: Novas diretrizes para vacinação são anunciadas',
    description: 'Ministério da Saúde divulga novas orientações para campanhas de vacinação em todo o país.',
    source: 'Saúde Brasil',
    date: '09/08/2025',
    image: null,
    url: '/news/category/health'
  }
])

const searchNews = () => {
  if (!searchForm.title.trim()) return
  window.location.href = `/news/search?title=${encodeURIComponent(searchForm.title)}`
}

const getCategoryFromTitle = (title) => {
  if (!title) return 'Notícia'
  
  const titleLower = title.toLowerCase()
  
  if (titleLower.includes('tecnologia') || titleLower.includes('tech') || titleLower.includes('ai') || titleLower.includes('inteligência artificial')) {
    return 'Tecnologia'
  }
  if (titleLower.includes('economia') || titleLower.includes('negócio') || titleLower.includes('mercado') || titleLower.includes('financeiro')) {
    return 'Economia'
  }
  if (titleLower.includes('saúde') || titleLower.includes('medicina') || titleLower.includes('hospital')) {
    return 'Saúde'
  }
  if (titleLower.includes('esporte') || titleLower.includes('futebol') || titleLower.includes('olímpico')) {
    return 'Esporte'
  }
  if (titleLower.includes('política') || titleLower.includes('governo') || titleLower.includes('eleição')) {
    return 'Política'
  }
  if (titleLower.includes('entretenimento') || titleLower.includes('filme') || titleLower.includes('música')) {
    return 'Entretenimento'
  }
  if (titleLower.includes('ciência') || titleLower.includes('pesquisa') || titleLower.includes('descoberta')) {
    return 'Ciência'
  }
  
  return 'Notícia'
}

const getPlaceholderStyle = (article) => {
  const category = getCategoryFromTitle(article.title)
  
  const gradients = {
    'Tecnologia': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    'Economia': 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
    'Saúde': 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
    'Esporte': 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
    'Política': 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
    'Entretenimento': 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
    'Ciência': 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
    'Notícia': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
  }
  
  return {
    background: gradients[category] || gradients['Notícia']
  }
}
</script>
