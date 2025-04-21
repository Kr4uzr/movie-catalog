<template>
    <div>
    <h1>Filmes Mais Bem Avaliados</h1><br>
    <div class="search-bar">
        <input
        v-model="searchQuery"
        @input="searchMovies"
        placeholder="Pesquisar filmes..."
        />
    </div><br>
    <div v-if="movies.length" class="movie-list">
        <div v-for="movie in movies" :key="movie.id" class="movie-item">
                <img
                :src="`https://image.tmdb.org/t/p/w500${movie.poster_path}`"
                :alt="movie.title"
                class="movie-poster"
                @click="openModal(movie)"
                />
            <div class="movie-info">
                <!-- Clique no nome redireciona -->
                <h3>{{ movie.title }}</h3>
                <div class="rating-container">
                    <p>Avaliação: {{ movie.vote_average.toFixed(1) }} / 10</p>
                    <button
                        @click.stop="toggleFavorite(movie)"
                        class="favorite-button">
                        <span v-if="isFavorite(movie.id)">★</span>
                        <span v-else>☆</span>
                    </button>
                </div>
            </div>
        </div>
    </div> 
    <div v-else>
        <p>Carregando...</p>
    </div><br>
        <div class="pagination">
            <button
            v-for="page in 10"
            :key="page"
            :class="{ active: currentPage === page }"
            @click="changePage(page)"
            >
            {{ page }}
            </button>
        </div>
    </div>
    <div v-if="showModal && selectedMovie" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
            <h2>{{ selectedMovie.title }}</h2><br>
            <img
            :src="`https://image.tmdb.org/t/p/w500${selectedMovie.poster_path}`"
            :alt="selectedMovie.title"
            />
            <p><strong>Descrição:</strong> {{ selectedMovie.overview }}</p>
            <p><strong>Data de Lançamento:</strong> {{ formatDate(selectedMovie.release_date) }}</p>
            <p><strong>Avaliação:</strong> {{ selectedMovie.vote_average.toFixed(1) }} / 10</p>
            <button @click="closeModal" class="close-button">Fechar</button>
        </div>
    </div>
</template>
  
<script lang="ts">
    import { defineComponent, ref } from 'vue';
    import axios from 'axios';
  
    interface Movie {
        id: number;
        title: string;
        vote_average: number;
        poster_path: string;
        overview: string;
        release_date: string;
    }

    interface Favorite {
        id: number; // ID do banco
        id_tmdb: number; // ID do TMDB
    }
  
    export default defineComponent({
    name: 'HomePage',
    setup() {
        const movies = ref<Movie[]>([]);
        const favorites = ref<Favorite[]>([]); // Lista de favoritos com IDs do banco e do TMDB
        const searchQuery = ref('');
        const currentPage = ref(1); // Página atual
        const showModal = ref(false);
        const selectedMovie = ref<Movie | null>(null);

        const openModal = (movie: Movie) => {
            selectedMovie.value = movie;
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

        const fetchMovies = async (page: number = 1) => {
            try {
                const response = await axios.get(`/api/movies/list-top-rateds?page=${page}`);
                movies.value = response.data.data.results;
            } catch (error) {
                console.error('Erro ao carregar filmes:', error);
            }
        };

        const changePage = (page: number) => {
            fetchMovies(page); // Chama a função fetchMovies com o número da página
        };

        const fetchFavorites = async () => {
            try {
                const response = await axios.get('/api/favorites');
                favorites.value = response.data.data; // Armazena os favoritos com IDs do banco
            } catch (error) {
                console.error('Erro ao carregar favoritos:', error);
            }
        };

        const searchMovies = async () => {
            if (!searchQuery.value) {
                fetchMovies(); // Se o campo de busca estiver vazio, carregue os filmes mais bem avaliados
                return;
            }

            try {
                const response = await axios.get(
                    `/api/movies/searchByName?name=${searchQuery.value}`
                );
                movies.value = response.data.data.results;
            } catch (error) {
                console.error('Erro ao buscar filmes:', error);
            }
    };

        const toggleFavorite = async (movie: Movie) => {
        const favorite = favorites.value.find((fav) => fav.id_tmdb === movie.id);

        if (favorite) {
            favorites.value = favorites.value.filter((fav) => fav.id !== favorite.id);
            try {
                await axios.delete(`/api/favorites/${favorite.id}`); // Envia o ID do banco
                favorites.value = favorites.value.filter((fav) => fav.id !== favorite.id);
            } catch (error) {
                console.error('Erro ao remover favorito:', error);
            }
        } else {
            try {
                const response = await axios.post('/api/favorites', {
                    id_tmdb: movie.id,
                    movie_title: movie.title,
                    poster_path: movie.poster_path,
                });

                //Adiciona o novo favorito com o ID do banco retornado pelo backend
                favorites.value.push(response.data.data);
            } catch (error) {
                console.error('Erro ao adicionar favorito:', error);
            }
        }
        };

        const isFavorite = (id_tmdb: number) => {
        return favorites.value.some((fav) => fav.id_tmdb === id_tmdb);
        };

        fetchMovies();
        fetchFavorites();

        return { movies, toggleFavorite, isFavorite, searchMovies, searchQuery, currentPage, changePage, showModal, openModal, closeModal,formatDate, selectedMovie};
    },
    });
</script>
  
<style scoped>

    .movie-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        transform: scale(1.05);
    }

    .movie-poster {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
    }

    .movie-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        text-align: center;
    }

    .movie-title {
        text-decoration: none;
        color: #ffffff;
    }

    .movie-title:hover h3 {
        text-decoration: underline;
    }

    .movie-info h3 {
        margin: 0;
        font-size: 18px;
    }

    .movie-info p {
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

    .search-bar {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .search-bar input {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #42b883;
        border-radius: 5px;
        width: 100%;
        max-width: 400px;
        background-color: #1e1e1e;
        color: #ffffff;
    }

    .search-bar input::placeholder {
        color: #42b883;
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
    }

    .modal-content {
        background-color: #1e1e1e;
        color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        text-align: center;
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