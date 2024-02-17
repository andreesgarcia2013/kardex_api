<?php

namespace App\Validators;

class UserValidator
{
    public static function rules()
    {
        return [
            'nombre'   =>  'required|string|max:255',
            'email'    =>  'required|email|unique:usuarios,email|max:255',
            'id_rol'   =>  'required|exists:roles,id_rol',
        ];
    }
}