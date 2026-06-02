import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // ទាញយក Vue Plugin មកប្រើ

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            // ដោះស្រាយបញ្ហា Runtime Compiler របស់ Vue
            vue: 'vue/dist/vue.esm-bundler.js', 
        },
    },
});