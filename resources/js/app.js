import './bootstrap'
import '../css/app.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import store from './store'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import PrimeVue from 'primevue/config'
import ToastService from 'primevue/toastservice'
import FloatingVue from 'floating-vue'
import ConfirmationService from 'primevue/confirmationservice'
import Button from 'primevue/button'
import Aura from '@primevue/themes/aura'
import ptbr from '../configs/pt-br.json'

const appName = 'Sistema Eletronico de Acompanhamento Legislativo'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(store)
      .use(ZiggyVue)
      .use(ConfirmationService)
      .component('Button', Button)
      .use(PrimeVue, {
        ripple: true,
        theme: {
          preset: Aura
        },
        options: {
          darkModeSelector: false || 'none'
        },
        locale: ptbr
      })
      .use(FloatingVue)
      .use(ToastService)
      .mount(el)
  },
  progress: {
    color: '#1e58af'
  }
})
