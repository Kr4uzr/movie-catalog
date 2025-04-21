import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../pages/HomePage.vue';
import FavoritesPage from '../pages/FavoritesPage.vue';
import MovieDetailsPage from '../pages/MovieDetailsPage.vue';

const routes = [
  { path: '/', name: 'Home', component: HomePage },
  { path: '/favorites', name: 'Favorites', component: FavoritesPage },
  { path: '/movie/:id', name: 'MovieDetails', component: MovieDetailsPage, props: true },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;