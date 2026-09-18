<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pokemon extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pokemons'; // Para el driver de MongoDB
    protected $table = 'pokemons';      // Para forzar el estándar de Laravel
}
