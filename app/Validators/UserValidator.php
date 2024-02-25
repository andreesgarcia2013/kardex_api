<?php

namespace App\Validators;

class UserValidator
{
    public static function rules()
    {
        return [
            'nombre'   =>  'required|string|max:255',
            'password' =>  'required|min:8',
            'email'    =>  'required|email|unique:usuarios,email|max:255',
            'id_rol'   =>  'exists:roles,id_rol',
        ];
    }

    public static function updateRules()
    {
        return [
            'nombre'   =>  'string|max:255',
            'email'    =>  'email|max:255',
        ];
    }

    public static function restorePasswordRules()
    {
        return [
            'email'        =>  'required|email|max:255',
            'old_password' => 'requiered',
            'new_password' =>  'required|min:8',
        ];
    }
}