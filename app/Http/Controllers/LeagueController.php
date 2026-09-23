<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\Pokemon;

class LeagueController extends Controller
{
    // 1. REGISTRAR RESULTADO DE PARTIDA (Afecta el dinero del Entrenador)
    public function registrarResultado(Request $request)
    {
        $request->validate([
            'entrenador_id' => 'required|string',
            'resultado' => 'required|in:victoria,derrota'
        ]);

        $entrenador = Trainer::find($request->entrenador_id);

        if (!$entrenador) {
            return response()->json(['error' => 'Entrenador no encontrado'], 404);
        }

        $economia = [
            'Poke Ball'   => ['gana' => 15,  'pierde' => 5],
            'Super Ball'  => ['gana' => 30,  'pierde' => 12],
            'Ultra Ball'  => ['gana' => 60,  'pierde' => 25],
            'Master Ball' => ['gana' => 100, 'pierde' => 50],
        ];

        $ligaActual = $entrenador->liga_actual ?? 'Poke Ball';
        $dineroBase = $economia[$ligaActual];

        if ($request->resultado === 'victoria') {
            $entrenador->racha_victorias = ($entrenador->racha_victorias ?? 0) + 1;

            $bonoRacha = 0;
            if ($entrenador->racha_victorias == 2) $bonoRacha = 5;
            elseif ($entrenador->racha_victorias == 3) $bonoRacha = 10;
            elseif ($entrenador->racha_victorias >= 4) $bonoRacha = 15;

            $dineroGanado = $dineroBase['gana'] + $bonoRacha;
            $entrenador->dinero_actual += $dineroGanado;
            $entrenador->puntos_tabla = ($entrenador->puntos_tabla ?? 0) + 3;

            $mensaje = "¡Victoria registrada! Ganaste $$dineroGanado ($$dineroBase[gana] + $$bonoRacha de bono).";
        } else {
            $entrenador->racha_victorias = 0;
            $entrenador->dinero_actual -= $dineroBase['pierde'];
            if ($entrenador->dinero_actual < 0) $entrenador->dinero_actual = 0;

            $mensaje = "Derrota registrada. Perdiste $$dineroBase[pierde] y tu racha volvió a 0.";
        }

        $entrenador->save();

        return response()->json([
            'message' => $mensaje,
            'entrenador' => $entrenador
        ]);
    }

    // 2. VENDER POKÉMON (Devuelve el dinero al entrenador y lo libera)
    public function venderPokemon(Request $request)
    {
        $request->validate([
            'entrenador_id' => 'required|string',
            'pokemon_id' => 'required|string'
        ]);

        $entrenador = Trainer::find($request->entrenador_id);
        $pokemon = Pokemon::find($request->pokemon_id);

        if (!$entrenador || !$pokemon) {
            return response()->json(['error' => 'Datos no encontrados'], 404);
        }

        $entrenador->dinero_actual += $pokemon->precio;

        $entrenador->equipo = collect($entrenador->equipo ?? [])
            ->reject(fn($pokemonId) => (string) $pokemonId === (string) $pokemon->getKey())
            ->values()
            ->all();
        $entrenador->save();

        // Limpiamos los campos basándonos en cómo los bloqueaste en PokemonController
        $pokemon->drafteado = false;
        $pokemon->entrenador = null;
        $pokemon->save();

        return response()->json([
            'message' => 'Pokémon vendido correctamente.',
            'nuevo_saldo' => $entrenador->dinero_actual
        ]);
    }

    // 3. OBTENER TABLA GENERAL
    public function getLeaderboard()
    {
        $leaderboard = Trainer::orderBy('puntos_tabla', 'desc')
            ->orderBy('dinero_actual', 'desc')
            ->get();

        return response()->json($leaderboard);
    }
}
