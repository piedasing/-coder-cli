import { createApp } from 'vue';

import App from './App.vue';
import plugins from '@/plugins/index.js';

import '@/scss/style.scss';

const searchParams = new URLSearchParams(window.location.search);
if (['1', 'true'].includes(searchParams.get('debug') || '')) {
    import('vconsole').then(({ default: VConsole }) => {
        new VConsole();
    });
}

const app = createApp(App);
app.use(plugins);
app.mount('#app');
