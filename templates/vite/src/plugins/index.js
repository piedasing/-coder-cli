import { createPinia } from 'pinia';
import piniaPersist from 'pinia-plugin-persist';
import { createHead } from '@unhead/vue';

import CoderLibrary from "@coder/core";
import "@coder/core/style.css";

import router from '@/router/index.js';

export default (app) => {
    app.use(router);
    app.use(createHead());

    const pinia = createPinia();
    pinia.use(piniaPersist);
    app.use(pinia);

    app.use(CoderLibrary);
};
