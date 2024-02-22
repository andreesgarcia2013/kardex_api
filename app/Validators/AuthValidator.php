<?php

namespace App\Validators;

class AuthValidator
{
    public static function rules(){
        return [
            'email' => 'required|email',
            'password' =>  'required',
        ];
    }
}