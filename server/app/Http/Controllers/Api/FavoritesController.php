<?php

namespace App\Http\Controllers\Api;

use App\Models\MoviesCatalogFavorites;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class FavoritesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Lista os filmes favoritos",
     *     tags={"Favorites"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de filmes favoritos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_tmdb", type="integer", description="ID do filme no TMDB"),
     *                     @OA\Property(property="movie_title", type="string", description="Título do filme"),
     *                     @OA\Property(property="overview", type="string", description="Resumo do filme"),
     *                     @OA\Property(property="poster_path", type="string", description="Caminho do poster do filme"),
     *                     @OA\Property(property="release_date", type="string", format="date", description="Data de lançamento"),
     *                     @OA\Property(property="rating", type="number", format="float", description="Nota média do filme"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", description="Data de adição aos favoritos")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $favorites = MoviesCatalogFavorites::select('id', 'id_tmdb', 'movie_title', 'overview', 'poster_path', 'release_date', 'rating', 'created_at')
            ->orderByDesc('created_at')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $favorites,
                'message' => 'Lista de filmes favoritos retornada com sucesso!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => "Erro ao buscar filmes favoritos: {$e->getMessage()}"],
                 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/favorites",
     *     summary="Adiciona um filme aos favoritos",
     *     tags={"Favorites"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_tmdb"},
     *             @OA\Property(property="id_tmdb", type="integer", description="ID do filme no TMDB")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Filme adicionado aos favoritos com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_tmdb", type="integer", description="ID do filme no TMDB"),
     *                 @OA\Property(property="movie_title", type="string", description="Título do filme"),
     *                 @OA\Property(property="overview", type="string", description="Resumo do filme"),
     *                 @OA\Property(property="poster_path", type="string", description="Caminho do poster do filme"),
     *                 @OA\Property(property="release_date", type="string", format="date", description="Data de lançamento"),
     *                 @OA\Property(property="rating", type="number", format="float", description="Nota média do filme"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Data de adição aos favoritos")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filme não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao adicionar filme aos favoritos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_tmdb' => 'required|integer|unique:movies_catalog_favorites,id_tmdb',
            ]);

            $TMDBController = new TMDBController();
            $movie = $TMDBController->searchById($validated['id_tmdb']);

            if ($movie->getStatusCode() !== 200) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'error' => 'Filme não encontrado!'],
                     404);
            }
            $movie = json_decode($movie->getContent(), true);

            $movieDetails = [
                'id_tmdb' => $movie['data']['id'],
                'movie_title' => $movie['data']['title'],
                'overview' => $movie['data']['overview'],
                'poster_path' => $movie['data']['poster_path'],
                'release_date' => $movie['data']['release_date'],
                'rating' => $movie['data']['vote_average'] ?? null,
            ];

            $favorite = MoviesCatalogFavorites::create($movieDetails);

            return response()->json([
                'success' => true,
                'data' => $favorite,
                'message' => 'Filme adicionado aos favoritos com sucesso!'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => "Erro ao adicionar filme aos favoritos: {$e->getMessage()}"],
                 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/favorites/{id}",
     *     summary="Remove um filme dos favoritos",
     *     tags={"Favorites"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do filme nos favoritos",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filme removido dos favoritos com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filme não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao remover filme dos favoritos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $favorite = MoviesCatalogFavorites::find($id);

            if($favorite === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Filme não encontrado!'],
                    404);
            }

            MoviesCatalogFavorites::where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Filme removido dos favoritos com sucesso!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => "Erro ao remover filme dos favoritos: {$e->getMessage()}"],
                 500);
        }
    }
}
