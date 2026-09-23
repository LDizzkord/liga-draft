<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;

class TrainerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:trainers,nombre'
        ]);

        $trainer = Trainer::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'message' => 'Entrenador registrado exitosamente.',
            'trainer' => $trainer
        ], 201);
    }

    // Opcional: Un endpoint para ver todos los entrenadores
    public function index()
    {
        return response()->json(Trainer::all());
    }
}
