<?php

namespace Unit\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa a rota de listagem de filmes mais bem avaliados.
     */
    public function test_list_top_rated_movies()
    {
        $response = $this->getJson('/api/movies/list-top-rateds');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'results' => [
                             '*' => [
                                'id',
                                'title',
                                'vote_average',
                                'poster_path',
                                'overview',
                                'release_date',
                                'vote_average',
                             ],
                         ],
                     ],
                 ]);
    }

    /**
     * Testa a rota de busca de filmes por nome.
     */
    public function test_search_movies_by_name()
    {
        $response = $this->getJson('/api/movies/searchByName?name=Inception');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'results' => [
                             '*' => [
                                'id',
                                'title',
                                'vote_average',
                                'poster_path',
                             ],
                         ],
                     ],
                 ]);
    }

    /**
     * Testa a rota de busca de filmes por ID.
     */
    public function test_search_movie_by_id()
    {
        $response = $this->getJson('/api/movies/searchById/424');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                        'id',
                        'title',
                        'vote_average',
                        'poster_path',
                        'overview',
                        'release_date',
                     ],
                 ]);
    }

    /**
     * Testa a rota de listagem de favoritos.
     */
    public function test_list_favorites()
    {
        $response = $this->getJson('/api/favorites');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                            'id',
                            'id_tmdb',
                            'movie_title',
                            'poster_path',
                            'rating',
                            'created_at',
                         ],
                     ],
                 ]);
    }

    /**
     * Testa a rota de criação de um favorito.
     */
    public function test_store_favorite()
    {
        $payload = [
            'id_tmdb' => 424,
        ];

        $response = $this->postJson('/api/favorites', $payload);
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                        'id',
                        'id_tmdb',
                        'movie_title',
                        'poster_path',
                        'rating',
                        'created_at',
                     ],
                 ]);
    }

    /**
     * Testa a rota de exclusão de um favorito.
     */
    public function test_delete_favorite()
    {
        // Primeiro, cria um favorito
        $payload = [
            'id_tmdb' => 424,
        ];

        $create = $this->postJson('/api/favorites', $payload);

        // Em seguida, tenta deletá-lo
        $response = $this->deleteJson("/api/favorites/{$create['data']['id']}");

        $response->assertStatus(200)
                 ->assertJson([
                    'success' => true,
                    'message' => 'Filme removido dos favoritos com sucesso!',
                 ]);
    }
}
