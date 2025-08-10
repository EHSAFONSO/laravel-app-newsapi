import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
    
    // Log para debug
    console.log('Available pages:', Object.keys(pages))
    console.log('Looking for page:', `./pages/${name}.vue`)
    
    // Tentar diferentes variações do nome, incluindo subdiretórios
    const possiblePaths = [
      `./pages/${name}.vue`,
      `./pages/${name.toLowerCase()}.vue`,
      `./pages/${name.charAt(0).toUpperCase() + name.slice(1)}.vue`,
      // Para subdiretórios como News/Index
      `./pages/${name.replace('/', '/').toLowerCase()}.vue`,
      `./pages/${name.replace('/', '/')}.vue`
    ]
    
    for (const path of possiblePaths) {
      if (pages[path]) {
        console.log('Found page at:', path)
        return pages[path]
      }
    }
    
    // Se não encontrou, tentar buscar por correspondência parcial
    const pageKeys = Object.keys(pages)
    const matchingPage = pageKeys.find(key => 
      key.toLowerCase().includes(name.toLowerCase().replace('/', '/'))
    )
    
    if (matchingPage) {
      console.log('Found page by partial match:', matchingPage)
      return pages[matchingPage]
    }
    
    console.error('Page not found. Available pages:', Object.keys(pages))
    throw new Error(`Page ${name} not found.`)
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin)
    app.mount(el)
  },
})
