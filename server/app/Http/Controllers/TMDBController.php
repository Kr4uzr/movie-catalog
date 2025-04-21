<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
     *             @OA\Property(property="results", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="page", type="integer"),
     *             @OA\Property(property="total_pages", type="integer"),
     *             @OA\Property(property="total_results", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao buscar filmes"
     *     )
     * )
     */
    public function listTopRateds(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);

        $response = Http::get("https://api.themoviedb.org/3/movie/top_rated", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'pt-BR',
            'page' => $page,
        ]);

        if ($response->failed()) {
            return response()->json(['error' => "Erro ao buscar filmes"], 500);
        }

        return response()->json($response->json());
    }

    /**
     * @OA\Get(
     *     path="/api/movies/search",
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
     *             @OA\Property(property="results", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="page", type="integer"),
     *             @OA\Property(property="total_pages", type="integer"),
     *             @OA\Property(property="total_results", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro: Nome não informado"
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $name = $request->query('name');

        if (!$name) {
            return response()->json(['error' => 'Necessário passar um nome para buscar!'], 400);
        }

        $response = Http::get("{$this->baseUrl}/search/movie", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => $this->language,
            'query' => $name,
        ]);

        return response()->json($response->json());
    }
}
