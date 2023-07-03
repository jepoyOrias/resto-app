
import { createApp, h } from 'vue';
import { createInertiaApp ,Link} from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import * as labsComponents from 'vuetify/labs/components'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import '@mdi/font/css/materialdesignicons.css'
import AOS from 'aos';
import 'aos/dist/aos.css';

const vuetify = createVuetify({
  ...components,
  ...directives,
  ...labsComponents,
  theme: {
    defaultTheme: 'myCustomTheme',
    themes: {
      myCustomTheme: {
        dark: false,
        colors: {
          light: "#FAFAFA",
          primary: '#43A047',
          warning: '#d08634'
        }
      }
    }
  },
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
})

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
AOS.init();
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    progress: false,
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify) 
            .component('Link', Link)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
});


