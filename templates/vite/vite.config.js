import path from 'path';
import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
import basicSsl from '@vitejs/plugin-basic-ssl';
import postcssImport from 'postcss-import';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import legacy from '@vitejs/plugin-legacy';
import { usePrerender } from '@coder/core/backend';

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd());
    const publicPath = env.VITE_PUBLIC_PATH || '/';

    return {
        env,
        base: publicPath,
        server: {
            host: '127.0.0.1',
            port: parseInt(env.VITE_PORT || 8001),
            https: true,
            open: false,
            proxy: {
                '/proxy-api': {
                    target: env.VITE_API_BASE,
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/proxy-api/, ''),
                },
            },
        },
        resolve: {
            alias: [
                { find: /\@\//, replacement: path.join(__dirname, './src/') },
            ],
        },
        css: {
            preprocessorOptions: {
                scss: {
                    additionalData: `
                        @import '@/scss/utils/_variables.scss';
                        @import '@/scss/utils/_mixins.scss';
                    `,
                },
            },
            postcss: {
                plugins: [postcssImport, autoprefixer, tailwindcss],
            },
        },
        build: {
            outDir: `dist/${mode}${publicPath}`,
            assetsDir: 'assets/img/',
            rollupOptions: {
                output: {
                    chunkFileNames: 'assets/js/[name]-[hash].js',
                    entryFileNames: 'assets/js/[name]-[hash].js',
                    assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
                },
            },
        },
        plugins: [
            basicSsl(),
            vue(),
            legacy({
                additionalLegacyPolyfills: ['regenerator-runtime/runtime'],
            }),
            usePrerender({
                staticDir: path.join(__dirname, 'dist', mode),
                outputDir: path.join(__dirname, 'dist', mode, publicPath),
                indexPath: path.join(__dirname, 'dist', mode, publicPath, 'index.html'), // prettier-ignore
                port: 8080,
                routes: ['/'],
            }),
        ],
    };
});
