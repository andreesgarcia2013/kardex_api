<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use App\Models\Alumno;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    public function login(Request $request){
            $rules = AuthValidator::rules();
            $credentials=$request->validate($rules);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('token')->plainTextToken;
                return response(["token"=>$token], Response::HTTP_OK);
            } else {
                return response(["message"=> "Credenciales inválidas"],Response::HTTP_UNAUTHORIZED);
            }      
    }

    public function userProfile(Request $request){
        $userData=auth()->user();
        $alumnoData=Alumno::where('id_usuario', $userData->id_usuario)->with('carrera')->first();
        if ($alumnoData) {
            // Si se encuentra un alumno relacionado, agrega sus datos a $userData
            $userData->alumnoData = $alumnoData;
        }
        return response()->json([
            "message" => "userProfile OK",
            "userData" => $userData
        ], 200);
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return response(["message"=>"Cierre de sesión OK"], Response::HTTP_OK);
    }
}