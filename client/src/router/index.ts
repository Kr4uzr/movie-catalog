import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../pages/HomePage.vue';
import FavoritesPage from '../pages/FavoritesPage.vue';

const routes = [
  { path: '/', name: 'Home', component: HomePage },
  { path: '/favorites', name: 'Favorites', component: FavoritesPage },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;