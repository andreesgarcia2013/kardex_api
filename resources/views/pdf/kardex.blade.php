<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        b {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Kardex</h1>
    <p><b>Nombre:</b>{{ $alumno }}</p>
    <p><b>Matricula:</b>{{ $matricula }}</p>
    <p><b>Carrera:</b>{{ $carrera }} <b> Grado:</b>{{ $grado }} <b> Promedio:</b>{{ $promedio }}</p>

    <table>
        <thead>
            <tr>
                <th><b>Codigo</b></th>
                <th><b>Materia</b></th>
                <th><b>Calificacion</b></th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí irán tus datos de materias -->
            @foreach($kardex as $itemKardex)
            <tr>
                <td>{{ $itemKardex->materia->codigo }}</td>
                <td>{{ $itemKardex->materia->materia }}</td>
                <td>{{ $itemKardex->calificacion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
