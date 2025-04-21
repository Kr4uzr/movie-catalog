<?php

use App\Http\Controllers\TMDBController;
use App\Http\Controllers\FavoritesController;
use Illuminate\Support\Facades\Route;

# TMDB Controller
Route::get('/movies/list-top-rateds', [TMDBController::class, 'listTopRateds']);
Route::get('/movies/search', [TMDBController::class, 'search']);

# Favorites Controller
Route::get('/favorites', [FavoritesController::class, 'index']);
Route::post('/favorites', [FavoritesController::class, 'store']);
Route::delete('/favorites/{id_tmdb}', [FavoritesController::class, 'delete']);
