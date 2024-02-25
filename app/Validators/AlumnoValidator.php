<?php

namespace App\Validators;

class AlumnoValidator
{
    public static function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|max:255',
            'password' =>  'required|min:8',
            'matricula'=> 'required|string|max:8',
            'grado'=> 'required|integer',
            'id_carrera'=> 'required',
        ];
    }

    public static function updateRules()
    {
        return [
            'nombre'   =>  'string|max:255',
            'password' =>  'min:8',
            'email'    =>  'email|max:255',
            'matricula'=>  'string|max:8',
            'grado'    =>  'integer',
            'id_carrera'=> 'exists:carreras,id_carrera',
        ];
    }
}