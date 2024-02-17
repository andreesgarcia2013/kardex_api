<?php

namespace App\Validators;

class AlumnoValidator
{
    public static function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email|max:255',
            'id_rol' => 'required|exists:roles,id_rol',
            'matricula'=> 'required|string|unique:alumnos,matricula|max:8',
            'grado '=> 'required',
            'id_carrera'=> 'required|exists:carreras,id_carrera',
        ];
    }
}