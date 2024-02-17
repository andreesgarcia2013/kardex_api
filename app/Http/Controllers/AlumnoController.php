<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use App\Models\Alumno;
use App\Models\Usuario;
use App\Validators\AlumnoValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AlumnoController extends Controller
{

    private $userController;

    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAlumno(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = AlumnoValidator::rules();
            $request->validate($rules);

            //Primero registramos el usuario con el se accede al sistema
            $user= new Usuario();

            $user -> nombre   =  $request->nombre;
            $user -> apellido =  $request->apellido;
            $user -> email    =  $request->email;
            $user -> id_rol   =  $request->id_rol;

            $password = Str::random(8); 
            $user -> password =  Hash::make($password);

            $user -> save();

            $alumno = new Alumno();
            $alumno -> matricula  = $request-> matricula;
            $alumno -> grado      = $request-> grado;
            $alumno -> id_carrera = $request-> id_carrera;
            $alumno -> id_usuario = $user->id_usuario;

            $alumno -> save();

            //Enviar las credenciales por correo
            $credentials = new Request();
            $credentials -> input( $user->email );
            $credentials -> input( $password );
            
            $emailResponse=$this->userController->sendCredentials($credentials);

            if ($emailResponse -> getStatusCode() != 200) {
                DB::rollBack();
                throw new \Exception($emailResponse['output']);
            }

            DB::commit();
            return JsonResponse::success('Proceso exitoso', $alumno);

        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al registrar el alumno', $th->getMessage());
        }
    }
}
