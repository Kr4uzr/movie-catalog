<template>
    <div>
    <h1>Filmes Favoritos</h1><br>
    <div v-if="favorites.length" class="movie-list">
        <div v-for="favorite in favorites" :key="favorite.id" class="movie-item">
        <img
            :src="`https://image.tmdb.org/t/p/w500${favorite.poster_path}`"
            :alt="favorite.movie_title"
            class="movie-poster"
            @click="openModal(favorite)"
        />
        <div class="movie-info">
            <h3>{{ favorite.movie_title }}</h3>
            <div class="rating-container">
                <p>Avaliação: {{ favorite.rating ? favorite.rating.toFixed(1) : 'N/A' }} / 10</p>
                <button
                    @click.stop="removeFavorite(favorite)"
                    class="favorite-button"
                >
                    ★
                </button>
            </div>
        </div>
        </div>
    </div>
    <div v-else>
        <p>Você ainda não possui filmes favoritos.</p>
    </div>
    <div v-if="showModal && selectedMovie" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
        <h2>{{ selectedMovie.movie_title }}</h2>
        <img
            :src="`https://image.tmdb.org/t/p/w500${selectedMovie.poster_path}`"
            :alt="selectedMovie.movie_title"
        />
        <p><strong>Descrição:</strong> {{ selectedMovie.overview || 'Descrição não disponível.' }}</p>
        <p><strong>Data de Lançamento:</strong> {{ formatDate(selectedMovie.release_date) || 'Data não disponível.' }}</p>
        <p><strong>Avaliação:</strong> {{ selectedMovie.rating ? selectedMovie.rating.toFixed(1) : 'N/A' }} / 10</p>
        <button @click="closeModal" class="close-button">Fechar</button>
        </div>
    </div>
    </div>
</template>
  
<script lang="ts">
    import { defineComponent, ref, onMounted } from 'vue';
    import axios from 'axios';

    interface Favorite {
        id: number;
        title: string;
        vote_average: number;
        poster_path: string;
        overview: string;
        release_date: string;
    }

    export default defineComponent({
        name: 'FavoritesPage',
        setup() {
            const favorites = ref<Favorite[]>([]);
            const showModal = ref(false);
            const selectedMovie = ref<Favorite | null>(null);

            const fetchFavorites = async () => {
                try {
                    const response = await axios.get('/api/favorites');
                    favorites.value = response.data.data;
                } catch (error) {
                    console.error('Erro ao carregar favoritos:', error);
                }
            };

            const removeFavorite = async (favorite: Favorite) => {
                try {
                    await axios.delete(`/api/favorites/${favorite.id}`);
                    favorites.value = favorites.value.filter((fav) => fav.id !== favorite.id);
                } catch (error) {
                    onsole.error('Erro ao remover favorito:', error);
                }
            };

            const openModal = (favorite: Favorite) => {
                selectedMovie.value = favorite;
                showModal.value = true;
            };

            const closeModal = () => {
                showModal.value = false;
                selectedMovie.value = null;
            };

            const formatDate = (date: string) => {
                if (!date) return 'Data não disponível';
                const [year, month, day] = date.split('-');
                return `${day}/${month}/${year}`;
            };

            onMounted(() => {
                fetchFavorites();
            });

            return { favorites, showModal, selectedMovie, fetchFavorites, removeFavorite, openModal, closeModal, formatDate };
        }
    });
</script>
  
<style scoped>
    .movie-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Layout responsivo */
        gap: 20px;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .movie-item {
        background-color: #1e1e1e;
        color: #ffffff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        transition: transform 0.2s ease;
    }

    .movie-item:hover {
        transform: scale(1.05); /* Efeito de zoom ao passar o mouse */
    }

    .movie-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        text-align: center;
    }

    .movie-info h3 {
        margin: 0;
        font-size: 18px;
    }

    .movie-title {
        text-decoration: none;
        color: #ffffff;
    }

    .movie-title:hover h3 {
        text-decoration: underline;
    }

    .rating-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }

    .rating-container p {
        margin: 0;
        font-size: 14px;
        color: #bbbbbb;
    }

    .favorite-button {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #f39c12;
    }

    .favorite-button:hover {
        color: #e67e22;
    }

    p {
        text-align: center;
        font-size: 1rem;
        color: #bbbbbb;
    }

    .movie-poster {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        overflow: hidden;
    }

    .modal-content {
        background-color: #1e1e1e;
        color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        max-height: 90%;
        overflow-y: auto;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    }

    .modal-content img {
        width: 50%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .modal-content p {
        margin: 10px 0;
        font-size: 16px;
        color: #bbbbbb;
        text-align: left;
    }

    .close-button {
        background-color: #42b883;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .close-button:hover {
        background-color: #369f6b;
    }
</style>