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
    // Función 1: Bloquea el Pokémon en el mercado
    public function draft($id)
    {
        $pokemon = Pokemon::find($id); // o el modelo que uses en MongoDB

        if (!$pokemon) {
            return response()->json(['error' => 'Pokémon no encontrado'], 404);
        }

        $pokemon->drafteado = true;
        $pokemon->save();

        return response()->json(['message' => 'Pokémon bloqueado exitosamente', 'pokemon' => $pokemon]);
    }

    // Función 2: Actualiza las estadísticas individuales
    public function updateStats(Request $request, $id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon) {
            return response()->json(['error' => 'Pokémon no encontrado'], 404);
        }

        // Extraemos el arreglo de performance actual
        $perf = $pokemon->performance ?? [
            'partidos_jugados' => 0,
            'derribos_totales' => 0,
            'veces_debilitado' => 0
        ];

        // Sumamos las nuevas estadísticas
        $perf['partidos_jugados'] += $request->input('jugados', 0);
        $perf['derribos_totales'] += $request->input('derribos', 0);
        $perf['veces_debilitado'] += $request->input('debilitado', 0);

        $pokemon->performance = $perf;
        $pokemon->save();

        return response()->json(['message' => 'Estadísticas actualizadas', 'pokemon' => $pokemon]);
    }
}
