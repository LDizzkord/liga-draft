<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    // Obtener todos los Pokémon para el Draft o la Pokédex
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Pokemon::all() // <-- CORREGIDO: Pokemon en singular
        ]);
    }

    // Buscar un Pokémon en específico por su nombre o ID
    public function show($id)
    {
        $pokemon = Pokemon::where('name', 'like', "%{$id}%")->orWhere('_id', $id)->first();

        if (!$pokemon) {
            return response()->json([
                'success' => false,
                'message' => 'Pokémon no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pokemon
        ]);
    }
}
