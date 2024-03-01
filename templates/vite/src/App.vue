<script setup>
import { ref, computed, onMounted } from 'vue';
import liff from '@line/liff';
import { useHead } from '@unhead/vue';
import { useNotify, useAjax } from '@coder/core';

const domain = import.meta.env.VITE_WEB_DOMAIN;
const publicPath = import.meta.env.VITE_PUBLIC_PATH;
const isPrerender = window?.__PRERENDER_INJECTED?.IS_PERRENDER || false;

useHead({
    titleTemplate: '%siteName %separator %s',
    templateParams: {
        separator: '|',
        siteName: import.meta.env.VITE_SITE_NAME,
        title: '',
        description: '',
    },
    title: '',
    link: [
        {
            rel: 'icon',
            type: 'image/png',
            sizes: '16x16',
            href: `${domain}${publicPath}favicon.ico`,
        },
    ],
    meta: [
        { name: 'title', content: '%title' },
        { name: 'description', content: '%description' },
        { name: 'og:type', content: 'website' },
        { property: 'og:title', content: '%title' },
        { property: 'og:description', content: '%description' },
        { property: 'og:image', content: `${domain}${publicPath}fb_share.jpg` },
        { property: 'og:url', content: `${domain}` },
        { property: 'twitter:card', content: 'summary_large_image' },
        { property: 'twitter:url', content: `${domain}` },
        { property: 'twitter:title', content: '%title' },
        { property: 'twitter:description', content: '%description' },
        {
            property: 'twitter:image',
            content: `${domain}${publicPath}fb_share.jpg`,
        },
    ],
    script: [
        {
            async: true,
            src: !isPrerender
                ? `https://www.googletagmanager.com/gtag/js?id=${
                      import.meta.env.VITE_GA4_ID
                  }`
                : '',
        },
        {
            use: () => {
                if (isPrerender) {
                    return;
                }
                if (!window.dataLayer) {
                    return;
                }

                window.dataLayer = window.dataLayer || [];
                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', import.meta.env.VITE_GA4_ID);
            },
        },
    ],
});

const $notify = useNotify();
const $ajax = useAjax({
    baseURL: import.meta.env.VITE_API_BASE,
    proxyPAth: import.meta.env.VITE_API_PROXY,
});

$notify.setGlobalConfigs({
    iconColor: '#0096FF',
    confirmButtonColor: '#F1341C',
    allowOutsideClick: true,
    allowEscapeKey: false,
});

const isInitSuccess = ref(false);
const isLoading = computed(() => $ajax.isLoading);

const initApp = async () => {
    try {
        $ajax.setLoading(true);
        await liff
            .init({ liffId: import.meta.env.VITE_LIFF_ID })
            .catch((error) => {
                throw error;
            });
        if (!liff.isLoggedIn()) {
            liff.login();
            return;
        }
        const accessToken = liff.getAccessToken();
        $ajax.setAuth(accessToken);

        isInitSuccess.value = true;
    } catch (error) {
        $notify
            .alert({
                title: '系統通知',
                message: error.message || '發生錯誤',
                variant: 'error',
            })
            .then(() => {
                if (liff.isInClient()) {
                    liff.close();
                    return;
                }
                window.close();
            });
    } finally {
        $ajax.setLoading(false);
    }
};

onMounted(() => {
    if (isPrerender) {
        setTimeout(() => {
            document.dispatchEvent(new Event('prerender-event'));
        }, 8000);
    }

    initApp();
});
</script>

<template>
    <template v-if="isLoading">
        <div
            class="tw-fixed tw-bottom-0 tw-left-0 tw-right-0 tw-top-0 tw-z-loading tw-flex tw-items-center tw-justify-center tw-bg-[rgba(0,0,0,0.4)]">
            <loader-spinner :size="'60px'" :color="'#fff'"></loader-spinner>
        </div>
    </template>
    <template v-if="isInitSuccess">
        <router-view></router-view>
    </template>
</template>

<style lang="scss">
body {
    background-color: #eee;
    &.swal2-height-auto.swal2-height-auto {
        height: 100% !important;
    }
}
#app {
    width: 100%;
    height: 100%;
}

.swal2-container {
    z-index: theme('zIndex.swal') !important;
}
</style>
