<?php

namespace App\Validators;

class KardexValidator {

    public static function rules(){
        return [
            'id_alumno' => 'required|exists:alumnos,id_alumno|integer',
            'materias' => 'required|array',
            'materias.*.id_materia' => 'required|integer|exists:materias,id_materia',
        ];
    }
}