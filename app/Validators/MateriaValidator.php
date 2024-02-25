<?php

namespace App\Validators;

class MateriaValidator
{
    public static function rules(){
        return [
            'codigo' => 'required|string|max:255',
            'materia' => 'required|max:255',
            'grado'=> 'required|integer',
            'calificacion_minima' => 'required|integer',
        ];
    }

    public static function updateRules(){
        return [
            'codigo' => 'string|max:255',
            'materia' => 'max:255',
            'grado'=> 'integer',
            'calificacion_minima' => 'integer',
        ];
    }
}