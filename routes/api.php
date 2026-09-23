<?php

use App\Http\Controllers\Api\PokemonController;
use Illuminate\Support\Facades\Route;


// La ruta que ya tenías
Route::get('/pokemons', [PokemonController::class, 'index']);

// Las 2 NUEVAS rutas para el mercado y las estadísticas
Route::patch('/pokemons/{id}/draft', [PokemonController::class, 'draft']);
Route::patch('/pokemons/{id}/stats', [PokemonController::class, 'updateStats']);

use App\Http\Controllers\LeagueController;
use App\Http\Controllers\TrainerController;

// Rutas existentes del catálogo Pokémon
Route::get('/pokemons', [PokemonController::class, 'index']);
Route::get('/pokemons/{id}', [PokemonController::class, 'show']);

// Rutas para gestionar Entrenadores
Route::get('/trainers', [TrainerController::class, 'index']);
Route::post('/trainers', [TrainerController::class, 'store']);

// Rutas para la gestión económica y resultados de la Liga Draft
Route::post('/partidas/registrar', [LeagueController::class, 'registrarResultado']);
Route::post('/pokemon/vender', [LeagueController::class, 'venderPokemon']);
Route::get('/leaderboard', [LeagueController::class, 'getLeaderboard']);
