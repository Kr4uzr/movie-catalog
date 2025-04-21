<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class TMDBController extends Controller
{
    private string $baseUrl = 'https://api.themoviedb.org/3';
    private string $language = 'pt-BR';

    /**
     * @OA\Get(
     *     path="/api/movies/list-top-rateds",
     *     summary="Retorna filmes mais bem avaliados",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página para paginação",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de filmes mais bem avaliados retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="results", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="page", type="integer"),
     *                 @OA\Property(property="total_pages", type="integer"),
     *                 @OA\Property(property="total_results", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao buscar filmes",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function listTopRateds(Request $request): JsonResponse
    {
        try {
            $page = $request->query('page', 1);

            $response = Http::get("{$this->baseUrl}/movie/top_rated", [
                'api_key' => env('TMDB_API_KEY'),
                'language' => $this->language,
                'page' => $page,
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json(),
                'message' => 'Lista de filmes mais bem avaliados retornada com sucesso!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => "Erro ao buscar filmes: {$e->getMessage()}"],
                 500);
        }

    }

    /**
     * @OA\Get(
     *     path="/api/movies/searchByName",
     *     summary="Busca filmes na API do TMDB com base no nome informado",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome do filme para buscar",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resultados da busca retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="results", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="page", type="integer"),
     *                 @OA\Property(property="total_pages", type="integer"),
     *                 @OA\Property(property="total_results", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro: Nome não informado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao buscar filme pelo nome",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function searchByName(Request $request): JsonResponse
    {
        try {
            $name = $request->query('name');

            $response = Http::get("{$this->baseUrl}/search/movie", [
                'api_key' => env('TMDB_API_KEY'),
                'language' => $this->language,
                'query' => $name,
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json(),
                'message' => 'Resultados da busca retornados com sucesso!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => "Erro ao buscar filme pelo nome: {$e->getMessage()}"],
             500);
        }

    }


    /**
     * @OA\Get(
     *     path="/api/movies/searchById/{id_tmdb}",
     *     summary="Busca detalhes de um filme pelo ID do TMDB",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="id_tmdb",
     *         in="path",
     *         description="ID do filme no TMDB",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do filme retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", description="ID do filme"),
     *                 @OA\Property(property="title", type="string", description="Título do filme"),
     *                 @OA\Property(property="overview", type="string", description="Resumo do filme"),
     *                 @OA\Property(property="poster_path", type="string", description="Caminho do poster do filme"),
     *                 @OA\Property(property="release_date", type="string", format="date", description="Data de lançamento"),
     *                 @OA\Property(property="vote_average", type="number", format="float", description="Nota média do filme"),
     *                 @OA\Property(property="vote_count", type="integer", description="Número de votos")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao buscar filme pelo ID",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function searchById($id_tmdb): JsonResponse
    {
        try {
            $response = Http::get("{$this->baseUrl}/movie/{$id_tmdb}", [
                'api_key' => env('TMDB_API_KEY'),
                'language' => $this->language,
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json(),
                'message' => 'Detalhes do filme retornados com sucesso!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => "Erro ao buscar filme pelo ID: {$e->getMessage()}"],
                 500);
        }
    }
}
