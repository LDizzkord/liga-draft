<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pokemon extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pokemons'; // Para el driver de MongoDB
    protected $table = 'pokemons';
    protected $fillable = [
        'name',
        'precio',
        'tier',
        'drafteado',
        'performance',
        'estrategia',
        'en_banquillo',
        'entrenador' // <-- ¡Este es el campo vital que faltaba!
    ];
}// Para forzar el estándar de Laravel
