import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS
                'resources/css/app.css',
                'resources/css/general.css',
                'resources/css/login.css',
                'resources/css/index.css',
                'resources/css/imagen.css',
                'resources/css/usuario.css',
                'resources/css/perfil.css',
                'resources/css/errors.css',

                // JS
                'resources/js/app.js',
                'resources/js/avatar.js',
                'resources/js/bubble.js',
                'resources/js/buscador.js',
                'resources/js/comments.js',
                'resources/js/contadorcaracter.js',
                'resources/js/likes.js',
                'resources/js/menusimagen.js',
                'resources/js/notificacion.js',
                'resources/js/recortador-perfil.js',
                'resources/js/recortador.js',
                'resources/js/scroll.js',
                'resources/js/seguir.js',
                'resources/js/usuario-galeria.js',
            ],
            refresh: true,
        }),
    ],

    server: {
        host: '0.0.0.0',
        hmr: {
            host: '127.0.0.1',
        },
    },
});