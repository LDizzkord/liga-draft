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
    public function draft(Request $request, $id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon) {
            return response()->json(['error' => 'Pokémon no encontrado'], 404);
        }

        $pokemon->drafteado = true;
        // NUEVO: Guardamos el nombre del jugador que mandó el frontend
        $pokemon->entrenador = $request->input('entrenador');
        $pokemon->save();

        return response()->json(['message' => 'Pokémon bloqueado exitosamente', 'pokemon' => $pokemon]);
    }

    public function updateStats(Request $request, $id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon) {
            return response()->json(['error' => 'Pokémon no encontrado'], 404);
        }

        // 1. Extraemos los datos que envía Angular
        $jugados = $request->input('jugados', 0);
        $derribos = $request->input('derribos', 0);
        $debilitado = $request->input('debilitado', 0);

        // 2. Actualizamos el arreglo de performance
        $perf = $pokemon->performance ?? [
            'partidos_jugados' => 0,
            'derribos_totales' => 0,
            'veces_debilitado' => 0
        ];

        $perf['partidos_jugados'] += $jugados;
        $perf['derribos_totales'] += $derribos;
        $perf['veces_debilitado'] += $debilitado;
        $pokemon->performance = $perf;

        // 3. LA MAGIA DEL MERCADO DINÁMICO
        $valorPorKill = 10;   // Sube $10 por matar
        $castigoPorMuerte = 5; // Baja $5 por morir

        $fluctuacion = ($derribos * $valorPorKill) - ($debilitado * $castigoPorMuerte);
        $nuevoPrecio = $pokemon->precio + $fluctuacion;

        // Evitamos que el precio baje de $10
        $pokemon->precio = max(10, $nuevoPrecio);

        $pokemon->save();

        return response()->json(['message' => 'Estadísticas y precio actualizados', 'pokemon' => $pokemon]);
    }
}
