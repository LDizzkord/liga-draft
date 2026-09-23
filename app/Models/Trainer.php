<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // Ajusta este namespace según el paquete de Mongo que uses

class Trainer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'trainers';

    protected $fillable = [
        'nombre',
        'dinero_actual',
        'puntos_tabla',
        'liga_actual',
        'racha_victorias',
        'equipo' // Array con los IDs de los Pokémon que ha drafteado
    ];

    // Valores por defecto cuando se crea un nuevo entrenador
    protected $attributes = [
        'dinero_actual' => 1000,
        'puntos_tabla' => 0,
        'liga_actual' => 'Poke Ball',
        'racha_victorias' => 0,
        'equipo' => []
    ];
}
