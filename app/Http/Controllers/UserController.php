<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use App\Models\Usuario;
use App\Validators\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = UserValidator::rules();
            $request->validate($rules);

            //Ahora podemos guardar al usuario
            $user= new Usuario();

            $user -> nombre   =  $request->nombre;
            $user -> apellido =  $request->apellido;
            $user -> email    =  $request->email;
            $user -> id_rol   =  $request->id_rol;
            $user -> password =  Hash::make($request->password);

            $user -> save();
            //Enviar las credenciales por correo (Se requiere servicio de mails)
            DB::commit();
            return JsonResponse::success('Proceso exitoso', $user);
        
        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al registrar el usuario', $th);
        }
    }

    public function sendCredentials(Request $user){
        try {
            Mail::send(
                'emails.credenciales',
                ['email' => $user->email, 'password' => $user->password],
                function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Hola, es una prueba');
                }
            );
            

            return JsonResponse::success('Proceso exitoso');

        } catch (\Throwable $th) {
            return JsonResponse::error('Error al enviar correo', $th);
        }
    }

    public function restorePassword(Request $request){
        try{
            DB::beginTransaction();

            $rules = UserValidator::restorePasswordRules();
            $request->validate($rules);

            $user=Usuario::where('email',$request->email)->first();
            if (!$user) {
                DB::rollBack();
                return JsonResponse::notFound('No se encontró el usuario');
            }
            
            if (!Hash::check($request->old_password, $user->password)) {
                return JsonResponse::notFound('Las credenciales no coinciden');
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            
            DB::commit();
            return JsonResponse::success('Proceso exitoso');
        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al restaurar las credenciales', $th);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_user)
    {
        try {
            DB::beginTransaction();

            $rules = UserValidator::updateRules();
            $request->validate($rules);

            if ($request->has('password')) {
                throw new \Exception('No puedes cambiar las credenciales desde aquí');
            }

            $user=Usuario::find($id_user);

            if (!$user) {
                DB::rollBack();
                return JsonResponse::notFound('No se encontró el usuario');
            }
            $user->update($request->all());

            DB::commit();
            return JsonResponse::success('Proceso exitoso', $user);

        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al actualizar el usuario', $th);
        }
    }

    public function index(){
        try {
            $admins=Usuario::where('id_rol', 1)->get();
            return JsonResponse::success('Proceso exitoso', $admins);
        } catch (\Throwable $th) {
            return JsonResponse::error('Error al recuperar los usuario', $th);
        }
    }

    public function show($id_user){
        try {
            $admin=Usuario::where('id_rol', 1)->where('id_usuario', $id_user)->first();
            if (!$admin) {
                return JsonResponse::notFound('No se encontró el usuario');
            }
            return JsonResponse::success('Proceso exitoso', $admin);
        } catch (\Throwable $th) {
            return JsonResponse::error('Error al recuperar el usuario', $th);
        }
    }
}
