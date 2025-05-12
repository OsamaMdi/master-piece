import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import os from 'os';

// 🔍 دالة ترجع أول IP من الشبكة (يعمل مع أي شبكة محلية)
function getLocalIP() {
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal) {
                return iface.address;
            }
        }
    }
    return 'localhost';
}

const localIP = getLocalIP();

export default defineConfig({
    server: {
        host: true,
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: localIP,
            protocol: 'ws',
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/auth.js',
            ],
            refresh: true,
        }),
    ],
});
