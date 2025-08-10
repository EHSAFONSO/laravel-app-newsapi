import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
    return pages[`./pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin)
    app.mount(el)
  },
})
