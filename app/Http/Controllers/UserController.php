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


    public function storeAdmin(Request $request)
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

            $password = Str::random(8); 
            $user -> password =  Hash::make($password);

            $user -> save();

            //Enviar las credenciales por correo
            $credentials = new Request();
            $credentials -> input( $user->email );
            $credentials -> input( $password );

            $emailResponse=$this->sendCredentials($credentials);
            
            if ($emailResponse -> getStatusCode() != 200) {
                DB::rollBack();
                throw new \Exception($emailResponse['output']);
            }

            DB::commit();
            return JsonResponse::success('Proceso exitoso', $user);
        
        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al registrar el usuario', $th->getMessage());
        }
    }

    public function sendCredentials(Request $user){
        try {
            Mail::send(
                'emails.credenciales',
                ['user' => $user->email, 'password' => $user->password],
                function ($message) use ($user){
                    $message->to($user->email)->subject('Credenciales de Usuario');
                }
            );

            return JsonResponse::success('Proceso exitoso');

        } catch (\Throwable $th) {
            return JsonResponse::error('Error al enviar el usuario', $th->getMessage());
        }
    }

    public function restorePassword(Request $request){
        try{
            DB::beginTransaction();
            $user=Usuario::find($request->id_user);

            if (!$user) {
                DB::rollBack();
                return JsonResponse::notFound('No se encontró el usuario');
            }

            $user->password = Hash::make($request->password);
            $user->save();

            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al restaurar las credenciales', $th->getMessage());
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
            $rules = UserValidator::rules();
            $request->validate($rules);

            $user=Usuario::find($id_user);

            if (!$user) {
                DB::rollBack();
                return JsonResponse::notFound('No se encontró el usuario');
            }
            $user->update($request->all);

            DB::commit();
            return JsonResponse::success('Proceso exitoso', $user);

        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al actualizar el usuario', $th->getMessage());
        }
    }
}
