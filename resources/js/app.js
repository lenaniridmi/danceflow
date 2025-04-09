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
import '@fortawesome/fontawesome-free/css/all.css'; // Добавляем стили Font Awesome

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import i18n from './i18n';
import vue3GoogleLogin from 'vue3-google-login';

const app = createApp(App);
app.use(router);
app.use(i18n);
app.use(vue3GoogleLogin, {
    clientId: 'YOUR_GOOGLE_CLIENT_ID'
});
app.mount('#app');