import { ref, watch, onMounted } from 'vue'

export function useTheme() {
  const isDark = ref(false)

  // Função para alternar o tema
  const toggleTheme = () => {
    isDark.value = !isDark.value
    updateTheme()
  }

  // Função para definir o tema
  const setTheme = (dark) => {
    isDark.value = dark
    updateTheme()
  }

  // Função para atualizar o tema no DOM
  const updateTheme = () => {
    const html = document.documentElement
    
    if (isDark.value) {
      html.classList.add('dark')
      html.setAttribute('data-theme', 'dark')
      localStorage.setItem('theme', 'dark')
    } else {
      html.classList.remove('dark')
      html.removeAttribute('data-theme')
      localStorage.setItem('theme', 'light')
    }
  }

  // Função para obter o tema inicial
  const getInitialTheme = () => {
    // Verificar se já há classe dark no HTML (inicialização)
    if (document.documentElement.classList.contains('dark')) {
      return true
    }
    
    // Verificar localStorage primeiro
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme) {
      return savedTheme === 'dark'
    }

    // Verificar preferência do sistema
    if (window.matchMedia) {
      return window.matchMedia('(prefers-color-scheme: dark)').matches
    }

    // Padrão para modo claro
    return false
  }

  // Inicializar o tema
  onMounted(() => {
    isDark.value = getInitialTheme()
    updateTheme()
  })

  // Observar mudanças na preferência do sistema
  if (window.matchMedia) {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
    mediaQuery.addEventListener('change', (e) => {
      // Só atualizar se não houver tema salvo no localStorage
      if (!localStorage.getItem('theme')) {
        isDark.value = e.matches
        updateTheme()
      }
    })
  }

  return {
    isDark,
    toggleTheme,
    setTheme,
    updateTheme
  }
}
