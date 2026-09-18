<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Http;

class ImportChampionsData extends Command
{
    protected $signature = 'champions:import-missing';
    protected $description = 'Importa los Pokémon faltantes desde la PokéAPI hacia MongoDB';

    public function handle()
    {
        $this->info('Consultando la PokéAPI para los Pokémon faltantes...');

        $pokemonsToFetch = [
            'Wigglytuff',
            'Persian',
            'Alolan Persian',
            'Farfetch’d',
            'Mr. Mime',
            'Swalot',
            'Salamence',
            'Gogoat',
            'Golisopod',
            'Rillaboom',
            'Cinderace',
            'Inteleon',
            'Thievul',
            'Toxtricity (Amped Form)',
            'Toxtricity (Low Key Form)',
            'Grapploct',
            'Perrserker',
            'Sirfetch’d',
            'Pincurchin',
            'Indeedee (Male)',
            'Indeedee (Female)',
            'Arboliva',
            'Squawkabilly (Green Plumage)',
            'Mabosstiff',
            'Baxcalibur'
        ];

        foreach ($pokemonsToFetch as $pokeName) {
            $slug = $this->formatSlug($pokeName);
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$slug}");

            if ($response->successful()) {
                $data = $response->json();
                Pokemon::updateOrCreate(
                    ['name' => $pokeName],
                    [
                        'tier' => 'Standard',
                        'price' => 10,
                        'format' => 'Singles/Doubles',
                        'pokeapi_id' => $data['id'],
                        'types' => collect($data['types'])->pluck('type.name')->toArray(),
                        'performance' => [
                            'partidos_jugados' => 0,
                            'derribos_totales' => 0,
                            'veces_debilitado' => 0
                        ]
                    ]
                );
                $this->info("Importado con éxito: {$pokeName}");
            } else {
                $this->error("No se pudo encontrar en la PokéAPI: {$pokeName} (slug: {$slug})");
            }
        }

        $this->info('¡Proceso de la PokéAPI finalizado!');
    }

    private function formatSlug($name)
    {
        $slug = strtolower($name);
        $slug = str_replace(['.', '’', '(', ')', ' form', ' plumage'], ['', '', '', '', '', ''], $slug);
        $slug = str_replace(['alolan ', 'amped ', 'low key ', 'green '], ['-alola', '-amped', '-low-key', '-green'], $slug);
        return trim(str_replace(' ', '-', $slug));
    }
}
