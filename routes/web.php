<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pokemon;
use Illuminate\Support\Facades\File;

Route::get('/migrar-datos', function () {
    $rutaJson = storage_path('app/pokemons.json');
    $datos = json_decode(File::get($rutaJson), true);

    // Limpia la colección para evitar duplicados si recargas la página
    Pokemon::truncate();

    // Inserta todos los registros directamente a Atlas
    Pokemon::insert($datos);

    return "¡Migración exitosa! Los datos ya están en Atlas.";
});

Route::get('/', function () {
    return view('pokemons');
});
