<?php

use App\Http\Controllers\Api\PokemonController;
use Illuminate\Support\Facades\Route;


// La ruta que ya tenías
Route::get('/pokemons', [PokemonController::class, 'index']);

// Las 2 NUEVAS rutas para el mercado y las estadísticas
Route::patch('/pokemons/{id}/draft', [PokemonController::class, 'draft']);
Route::patch('/pokemons/{id}/stats', [PokemonController::class, 'updateStats']);
