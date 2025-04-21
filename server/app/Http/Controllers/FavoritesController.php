<?php

namespace App\Http\Controllers;

use App\Models\MoviesCatalogFavorites;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id_tmdb", type="integer"),
     *                 @OA\Property(property="movie_title", type="string"),
     *                 @OA\Property(property="poster_path", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $favorites = MoviesCatalogFavorites::select('id_tmdb', 'movie_title', 'overview', 'poster_path', 'release_date', 'rating', 'created_at')
            ->orderByDesc('created_at')
            ->get();

            if($favorites->isEmpty()) {
                return response()->json(['message' => 'Nenhum filme favorito encontrado!'], 404);
            }

            return response()->json($favorites);
        } catch (Exception $e) {
            return response()->json(['error' => "Erro ao buscar filmes favoritos: {$e->getMessage()}"], 500);
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
     *             @OA\Property(property="id_tmdb", type="integer", description="ID do filme no TMDB"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Filme adicionado aos favoritos com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id_tmdb", type="integer"),
     *             @OA\Property(property="movie_title", type="string"),
     *             @OA\Property(property="overview", type="string"),
     *             @OA\Property(property="poster_path", type="string"),
     *             @OA\Property(property="realease_date", type="string"),
     *             @OA\Property(property="rating", type="float"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
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
                return response()->json(['error' => 'Filme não encontrado!'], 404);
            }
            $movie = json_decode($movie->getContent(), true);

            $movieDetails = [
                'id_tmdb' => $movie['id'],
                'movie_title' => $movie['title'],
                'overview' => $movie['overview'],
                'poster_path' => $movie['poster_path'],
                'release_date' => $movie['release_date'],
                'rating' => $movie['vote_average'] ?? null,
            ];

            $favorite = MoviesCatalogFavorites::create($movieDetails);

            return response()->json($favorite, 201);
        } catch (Exception $e) {
            if ($e->getCode() === 422) {
                return response()->json(['error' => 'Erro de validação: ' . $e->getMessage()], 422);
            }

            return response()->json(['error' => "Erro ao adicionar filme aos favoritos: {$e->getMessage()}"], 500);
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
     *         description="ID do filme",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filme removido dos favoritos com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filme não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     * )
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $favorite = MoviesCatalogFavorites::find($id);

            if($favorite === null) {
                return response()->json(['message' => 'Filme não encontrado!'], 404);
            }

            MoviesCatalogFavorites::where('id', $id)->delete();

            return response()->json(['message' => 'Filme removido dos favoritos com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['error' => "Erro ao remover filme dos favoritos: {$e->getMessage()}"], 500);
        }
    }
}
