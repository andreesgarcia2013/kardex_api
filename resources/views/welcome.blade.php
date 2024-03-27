<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Tarjetas</title>
</head>
<body>
    <h1>Generar Tarjetas</h1>
    <form action="{{ route('generatecards') }}" method="post"> <!-- Utilizando el método route() -->
        @csrf
        <label for="num_cards">Número de Tarjetas:</label><br>
        <input type="number" id="num_cards" name="num_cards" min="1" required><br><br>
        <button type="submit">Generar Tarjetas</button>
    </form>
</body>
</html>
