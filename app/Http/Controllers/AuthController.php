<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
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
                $cookie = cookie('cookie_token', $token, 60 * 24);
                return response(["token"=>$token], Response::HTTP_OK)->withCookie($cookie);
            } else {
                return response(["message"=> "Credenciales inválidas"],Response::HTTP_UNAUTHORIZED);
            }      
    }

    public function userProfile(Request $request){
        return response()->json([
            "message" => "userProfile OK",
            "userData" => auth()->user()
        ], 200);
    }

    public function logout() {
        $cookie = Cookie::forget('cookie_token');
        auth()->user()->tokens()->delete();
        return response(["message"=>"Cierre de sesión OK"], Response::HTTP_OK)->withCookie($cookie);
    }
}