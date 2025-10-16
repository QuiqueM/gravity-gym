import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        VitePWA({
            registerType: 'autoUpdate',
            manifestFilename: 'pwa-manifest.json',
            strategies: 'generateSW',
            swDest: 'public/sw.js',
            workbox: {
                importScripts: ['https://storage.googleapis.com/workbox-cdn/releases/7.0.0/workbox-sw.js'],
                globPatterns: ['**/*.{js,css,png,svg,ico}'],
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/fonts\.googleapis\.com\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'google-fonts-cache',
                            expiration: {
                                maxEntries: 10,
                                maxAgeSeconds: 60 * 60 * 24 * 365 // <== 365 días
                            },
                            cacheableResponse: {
                                statuses: [0, 200]
                            }
                        }
                    }
                ]
            },
            devOptions: {
                enabled: true,
                type: 'module',
            },
            manifest: {
                name: 'Gravity Fit MX',
                short_name: 'Gravity',
                start_url: '/',
                scope: '/',
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
        }),
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
    ],
});