<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pokemon;

class ActualizarRankings extends Command
{
    protected $signature = 'draft:rankings';
    protected $description = 'Actualiza el ranking y precio directo en MongoDB Atlas';

    public function handle()
    {
        $valores = [
            'S' => 250,
            'A' => 200,
            'B' => 150,
            'C' => 100,
            'D' => 50
        ];

        $rankings = [
            'S' => [
                "Rillaboom",
                "Sneasler",
                "Salamence",
                "Incineroar",
                "Kingambit",
                "Indeedee (Hembra)",
                "Basculegion (Macho)",
                "Golisopod",
                "Pelipper",
                "Garchomp",
                "Gholdengo",
                "Archaludon",
                "Farigiraf",
                "Milotic",
                "Gardevoir",
                "Charizard",
                "Arcanine",
                "Sylveon",
                "Armarouge",
                "Raichu"
            ],
            'A' => [
                "Tyranitar",
                "Whimsicott",
                "Floette",
                "Torkoal",
                "Sinistcha",
                "Staraptor",
                "Indeedee (Macho)",
                "Lucario",
                "Excadrill",
                "Metagross",
                "Volcarona",
                "Politoed",
                "Grimmsnarl",
                "Baxcalibur",
                "Swampert",
                "Pawmot",
                "Froslass",
                "Ninetales de Alola",
                "Glimmora",
                "Venusaur",
                "Primarina",
                "Gengar",
                "Dragonite",
                "Aerodactyl",
                "Sableye",
                "Hatterene",
                "Absol",
                "Blastoise",
                "Annihilape",
                "Delphox",
                "Maushold (Familia de Tres)",
                "Talonflame",
                "Kommo-o",
                "Camerupt",
                "Ceruledge",
                "Hydreigon",
                "Mawile",
                "Blaziken",
                "Corviknight",
                "Gallade"
            ],
            'B' => [
                "Typhlosion",
                "Inteleon",
                "Tsareena",
                "Rotom Lavado",
                "Sirfetch’d",
                "Zoroark",
                "Dragapult",
                "Scovillain",
                "Vivillon",
                "Kleavor",
                "Alakazam",
                "Weavile",
                "Meowscarada",
                "Empoleon",
                "Espathra",
                "Toxapex",
                "Toxtricity (Forma Aguda)",
                "Chandelure",
                "Aegislash (Forma Escudo)",
                "Scizor",
                "Pincurchin",
                "Cinderace",
                "Clefable",
                "Malamar",
                "Mamoswine",
                "Meganium",
                "Lycanroc (Forma Crepuscular)",
                "Kangaskhan",
                "Mimikyu",
                "Basculegion (Hembra)",
                "Samurott",
                "Rotom Calor",
                "Scrafty",
                "Oranguru",
                "Overqwil",
                "Greninja",
                "Toxtricity (Forma Grave)",
                "Goodra",
                "Araquanid",
                "Snorlax",
                "Drampa",
                "Gyarados",
                "Grapploct",
                "Tinkaton",
                "Pyroar",
                "Arcanine",
                "Bellibolt",
                "Vanilluxe",
                "Meowstic (Macho)",
                "Slowking de Galar",
                "Crabominable",
                "Ninetales",
                "Starmie",
                "Klefki",
                "Sceptile",
                "Skeledirge",
                "Audino",
                "Skarmory",
                "Abomasnow",
                "Aggron"
            ],
            'C' => [
                "Raichu de Alola",
                "Slowbro",
                "Ampharos",
                "Umbreon",
                "Hawlucha",
                "Steelix",
                "Meowstic (Hembra)",
                "Jolteon",
                "Spiritomb",
                "Lopunny",
                "Perrserker",
                "Slowbro de Galar",
                "Houndstone",
                "Clawitzer",
                "Azumarill",
                "Altaria",
                "Conkeldurr",
                "Dragalge",
                "Persian de Alola",
                "Wigglytuff",
                "Heliolisk",
                "Palafin",
                "Gliscor",
                "Chesnaught",
                "Houndoom",
                "Ditto",
                "Vileplume",
                "Eelektross",
                "Golurk",
                "Manectric",
                "Infernape",
                "Wyrdeer",
                "Feraligatr",
                "Mudsdale",
                "Noivern",
                "Alcremie",
                "Arboliva",
                "Cofagrigus",
                "Decidueye",
                "Krookodile",
                "Torterra",
                "Hippowdon",
                "Rhyperior",
                "Glaceon",
                "Espeon",
                "Salazzle",
                "Serperior",
                "Mr. Mime",
                "Reuniclus",
                "Medicham",
                "Aurorus",
                "Vaporeon",
                "Typhlosion",
                "Aromatisse",
                "Heracross",
                "Scolipede",
                "Glalie",
                "Runerigus",
                "Tauros de Paldea (Raza Ardiente)",
                "Liepard",
                "Tauros de Paldea (Raza Acuática)",
                "Mabosstiff",
                "Machamp",
                "Orthworm",
                "Banette",
                "Zoroark",
                "Beedrill",
                "Hydrapple",
                "Slowking",
                "Quaquaval",
                "Mr. Rime",
                "Squawkabilly (Plumaje Verde)",
                "Morpeko",
                "Toxicroak",
                "Garganacl",
                "Pikachu",
                "Gogoat",
                "Farfetch’d",
                "Squawkabilly (Plumaje Amarillo)",
                "Sharpedo"
            ]
        ];

        $contadorTotal = 0;

        foreach ($rankings as $ranking => $pokemons) {
            $precio = $valores[$ranking];

            // MongoDB es sensible a mayúsculas. Aseguramos que busque coincidencias exactas.
            $afectados = Pokemon::whereIn('name', $pokemons)->update([
                'tier' => $ranking,
                'precio' => $precio
            ]);

            $contadorTotal += $afectados;
            $this->info("Tier {$ranking}: Se actualizaron {$afectados} Pokémon con precio \${$precio}");
        }

        // El comodín para la Tier D
        $huerfanos = Pokemon::whereNull('precio')->update([
            'tier' => 'D',
            'precio' => $valores['D']
        ]);

        $contadorTotal += $huerfanos;
        $this->info("Tier D (Restantes): Se actualizaron {$huerfanos} Pokémon con precio \${$valores['D']}");
        $this->info("¡Éxito! {$contadorTotal} registros modificados en MongoDB Atlas.");
    }
}
