import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/css/header.css',
        'resources/css/footer.css',
        'resources/css/home.css',
        'resources/css/login.css',
        'resources/css/register.css',
        'resources/css/profile.css',
        'resources/css/favorites.css',
        'resources/css/courses.css',
        'resources/css/messages.css',
        'resources/css/progress.css',
        'resources/css/reviews.css',
        'resources/css/dashboard.css',
        'resources/css/video-player.css',
        'resources/js/app.js',
    ],
    refresh: true,
}),
vue(),
  ],
  server: {
    hmr: { host: 'localhost' },
  },
  css: {
    postcss: './postcss.config.cjs',
  },
});