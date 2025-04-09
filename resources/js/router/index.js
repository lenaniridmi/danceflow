import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    { path: '/', name: 'home', component: () => import('../components/Home.vue') },
    { path: '/dashboard', name: 'dashboard', component: () => import('../components/DashboardPage.vue') },
    { path: '/courses', name: 'courses', component: () => import('../components/CoursesPage.vue') },
    { path: '/progress', name: 'progress', component: () => import('../components/ProgressPage.vue') },
    { path: '/messages', name: 'messages', component: () => import('../components/MessagesPage.vue') },
    { path: '/favorites', name: 'favorites', component: () => import('../components/FavoritesPage.vue') },
    { path: '/reviews', name: 'reviews', component: () => import('../components/ReviewsPage.vue') },
    { path: '/login', name: 'login', component: () => import('../components/Login.vue') },
    { path: '/register', name: 'register', component: () => import('../components/Register.vue') },
    { path: '/profile', name: 'profile', component: () => import('../components/Profile.vue') },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;