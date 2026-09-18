<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pokemon Draft</title>
    <style>
        :root { font-family: system-ui, sans-serif; color: #172033; background: #f4f7fb; }
        body { margin: 0; padding: 2rem; }
        main { max-width: 1100px; margin: 0 auto; }
        h1 { margin-bottom: .35rem; }
        .status { color: #5d687d; margin-top: 0; }
        .error { color: #a12626; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; background: white; }
        th, td { padding: .8rem 1rem; text-align: left; border-bottom: 1px solid #e3e8f0; }
        th { background: #e9eef7; }
        @media (max-width: 600px) { body { padding: 1rem; } th, td { padding: .65rem .5rem; } }
    </style>
</head>
<body>
<main>
    <h1>Pokemon Draft</h1>
    <p id="status" class="status">Cargando datos desde MongoDB Atlas...</p>
    <table aria-label="Pokemon disponibles">
        <thead>
        <tr><th>Nombre</th><th>Tier</th><th>Precio</th><th>Formato</th></tr>
        </thead>
        <tbody id="pokemon-list"></tbody>
    </table>
</main>
<script>
    fetch('{{ url('/api/pokemons') }}')
        .then(response => {
            if (!response.ok) throw new Error('La API respondió con HTTP ' + response.status);
            return response.json();
        })
        .then(({ data }) => {
            const list = document.getElementById('pokemon-list');
            data.forEach(pokemon => {
                const row = document.createElement('tr');
                [pokemon.name, pokemon.tier ?? '-', pokemon.price ?? '-', pokemon.format ?? '-']
                    .forEach(value => {
                        const cell = document.createElement('td');
                        cell.textContent = value;
                        row.appendChild(cell);
                    });
                list.appendChild(row);
            });
            document.getElementById('status').textContent = `${data.length} Pokemon cargados desde Atlas.`;
        })
        .catch(error => {
            const status = document.getElementById('status');
            status.textContent = 'No se pudieron cargar los Pokemon: ' + error.message;
            status.classList.add('error');
        });
</script>
</body>
</html>
