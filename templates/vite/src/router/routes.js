export default [
    {
        path: '/',
        name: 'home',
        component: () => import('@/views/Home.vue'),
    },
    {
        path: '/:pathName(.*)',
        redirect: () => ({ name: 'home' }),
    },
];
