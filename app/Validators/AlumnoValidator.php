<?php

namespace App\Validators;

class AlumnoValidator
{
    public static function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email|max:255',
            'password' =>  'required|min:8',
            'id_rol' => 'required|exists:roles,id_rol',
            'matricula'=> 'required|string|unique:alumnos,matricula|max:8',
            'grado'=> 'required|integer',
            'id_carrera'=> 'required|exists:carreras,id_carrera',
        ];
    }

    public static function updateRules()
    {
        return [
            'nombre'   =>  'string|max:255',
            'password' =>  'min:8',
            'email'    =>  'email|unique:usuarios,email|max:255',
            'matricula'=>  'string|unique:alumnos,matricula|max:8',
            'grado'    =>  'integer',
            'id_carrera'=> 'exists:carreras,id_carrera',
        ];
    }
}