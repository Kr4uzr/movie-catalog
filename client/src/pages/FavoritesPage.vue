<template>
    <div>
      <h1>Filmes Favoritos</h1><br>
      <div v-if="favorites.length" class="movie-list">
        <div v-for="favorite in favorites" :key="favorite.id" class="movie-item">
          <router-link :to="`/movie/${favorite.id_tmdb}`">
            <img
              :src="`https://image.tmdb.org/t/p/w500${favorite.poster_path}`"
              :alt="favorite.movie_title"
              class="movie-poster"
            />
          </router-link>
          <div class="movie-info">
            <!-- Clique no nome redireciona -->
            <router-link :to="`/movie/${favorite.id_tmdb}`" class="movie-title">
              <h3>{{ favorite.movie_title }}</h3>
            </router-link>
            <div class="rating-container">
                <p>Avaliação: {{ favorite.rating.toFixed(1) }} / 10</p>
              <button
                @click.stop="removeFavorite(favorite)"
                class="favorite-button">
                ★
              </button>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <p>Você ainda não possui filmes favoritos.</p>
      </div>
    </div>
  </template>
  
<script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        favorites: [],
      };
    },
    methods: {
        async fetchFavorites() {
            try {
            const response = await axios.get('/api/favorites');
            this.favorites = response.data.data;
            } catch (error) {
            console.error('Erro ao carregar favoritos:', error);
            }
        },
        async removeFavorite(favorite) {
            try {
            await axios.delete(`/api/favorites/${favorite.id}`);
            this.favorites = this.favorites.filter((fav) => fav.id !== favorite.id);
            } catch (error) {
            console.error('Erro ao remover favorito:', error);
            }
        },
    },
    async created() {
      this.fetchFavorites();
    },
  };
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
</style>