<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoviesCatalogFavorites extends Model
{
    protected $fillable = [
        'id_tmdb',
        'movie_title',
        'overview',
        'poster_path',
        'release_date',
        'rating',
    ];
}
