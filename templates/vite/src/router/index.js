import { createRouter, createWebHistory } from 'vue-router';

import routes from './routes.js';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            return;
        }
        return { left: 0, top: 0 };
    },
});

export default router;
