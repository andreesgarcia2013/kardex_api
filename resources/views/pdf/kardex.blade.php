<h1>Kardex</h1>
<p><b>Nombre:</b>{{$alumno}}</p>
<p><b>Matricula:</b>{{$matricula}}</p>
<p><b>Carrera:</b>{{$carrera}} <b> Grado:</b>{{$grado}} <b> Promedio:</b>{{$promedio}}</p>

<table>
    <tr>
        <td><b>Codigo</b></td>
        <td><b>Materia</b></td>
        <td><b>Calificacion</b></td>
    </tr>
    <!-- Aquí irán tus datos de materias -->
    @foreach($kardex as $itemKardex)
    <tr>
        <td>{{ $itemKardex->materia->codigo }}</td>
        <td>{{ $itemKardex->materia->materia }}</td>
        <td>{{ $itemKardex->calificacion }}</td>
    </tr>
    @endforeach


</table>

