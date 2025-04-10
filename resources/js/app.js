import './bootstrap'; // Импортируем bootstrap.js первым

import '../css/app.css';
import '../css/header.css';
import '../css/footer.css';
import '../css/home.css';
import '../css/login.css';
import '../css/register.css';
import '../css/profile.css';
import '../css/favorites.css';
import '../css/courses.css';
import '../css/messages.css';
import '../css/progress.css';
import '../css/reviews.css';
import '../css/dashboard.css';
import '../css/video-player.css';
import '@fortawesome/fontawesome-free/css/all.css';

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import i18n from './i18n';
import vue3GoogleLogin from 'vue3-google-login';

const app = createApp(App);
app.use(router);
app.use(i18n);
app.use(vue3GoogleLogin, {
    clientId: '456217167788-52ae8hj3cesunje8daajjadd85r9mq87.apps.googleusercontent.com',
    redirectUri: 'http://127.0.0.1:8000/api/auth/google/callback',
});
app.mount('#app');