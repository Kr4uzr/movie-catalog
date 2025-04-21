<template>
  <div>
    <h1>{{ movie.title }}</h1>
    <img :src="`https://image.tmdb.org/t/p/w500${movie.poster_path}`" :alt="movie.title" />
    <p><strong>Sinopse:</strong> {{ movie.overview }}</p><br>
    <p><strong>Data de Lançamento:</strong> {{ formatDate(movie.release_date) }}</p><br>
    <p><strong>Avaliação:</strong> {{ movie.vote_average.toFixed(1) }} / 10</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      movie: {},
    };
  },
  methods: {
    formatDate(date) {
      if (!date) return 'Data não disponível';
      const [year, month, day] = date.split('-');
      return `${day}/${month}/${year}`;
    },
  },
  async created() {
    try {
      const response = await axios.get(`/api/movies/searchById/${this.$route.params.id}`);
      this.movie = response.data.data;
    } catch (error) {
      console.error('Erro ao carregar detalhes do filme:', error);
    }
  },
};
</script>

<style scoped>

  h1 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #42b883;
  }

  img {
    width: 100%;
    max-width: 400px;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  p {
    text-align: left;
    font-size: 1rem;
    margin: 10px 0;
  }
</style>