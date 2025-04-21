<?php

use App\Http\Controllers\TMDBController;
use App\Http\Controllers\FavoritesController;
use Illuminate\Support\Facades\Route;

# TMDB Controller
Route::get('/movies/list-top-rateds', [TMDBController::class, 'listTopRateds']);
Route::get('/movies/searchByName', [TMDBController::class, 'searchByName']);
Route::get('/movies/searchById/{id_tmdb}', [TMDBController::class, 'searchById']);

# Favorites Controller
Route::get('/favorites', [FavoritesController::class, 'index']);
Route::post('/favorites', [FavoritesController::class, 'store']);
Route::delete('/favorites/{id}', [FavoritesController::class, 'delete']);
