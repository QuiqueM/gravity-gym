import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'autoUpdate',
            manifestFilename: 'manifest.json',
            strategies: 'injectManifest',
            srcDir: 'resources/js',
            filename: 'sw.js',
            injectManifest: {
                injectionPoint: undefined,
            },
            manifest: {
                name: 'Gravity Fit MX',
                short_name: 'Gravity',
                start_url: '/',
                display: 'standalone',
                background_color: '#181411',
                theme_color: '#2785bd',
                    icons: [
                        {
                            src: '/icon-196x196.png',
                            sizes: '192x192',
                            type: 'image/png',
                            purpose: 'any maskable'
                        },
                        {
                            src: '/icon-512x512.png',
                            sizes: '512x512',
                            type: 'image/png',
                            purpose: 'any maskable'
                        },
                        {
                            src: '/icon-256x256.png',
                            sizes: '256x256',
                            type: 'image/png',
                            purpose: 'any maskable'
                        },
                    ]
            }
        })
    ],
});