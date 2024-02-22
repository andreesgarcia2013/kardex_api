<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use App\Models\Alumno;
use App\Models\User;
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
            $user= new User();

            $user -> nombre   =  $request->nombre;
            $user -> apellido =  $request->apellido;
            $user -> email    =  $request->email;
            $user -> password =  Hash::make($request->password);
            $user -> id_rol   =  $request->id_rol;

            $user -> save();

            $alumno = new Alumno();
            $alumno -> matricula  = $request-> matricula;
            $alumno -> grado      = $request-> grado;
            $alumno -> id_carrera = $request-> id_carrera;
            $alumno -> id_usuario = $user->id_usuario;

            $alumno -> save();
            //Enviar las credenciales por correo (Se requiere servicio de mails)
            DB::commit();
            return JsonResponse::success('Proceso exitoso', $alumno);

        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al registrar el alumno', $th);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_alumno)
    {
        try {
            DB::beginTransaction();
            $rules = AlumnoValidator::updateRules();
            $request->validate($rules);
            if ($request->has('password')) {
                throw new \Exception('No puedes cambiar las credenciales desde aquí');
            }
            $alumno=Alumno::find($id_alumno);

            if (!$alumno) {
                DB::rollBack();
                return JsonResponse::notFound('No se encontró el usuario');
            }
            $alumno->update($request->all());

            $userResponse=$this->userController->update($request, $alumno->id_usuario);

            if ($userResponse -> getStatusCode() != 200) {
                $dataArray = json_decode($userResponse->getContent());
                DB::rollBack();
                throw new \Exception($dataArray->getMessage);
            }
            
            DB::commit();
            return JsonResponse::success('Proceso exitoso', $alumno);

        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al actualizar el usuario', $th);
        }
    }

    public function index(){
        try {
            $alumnos=Alumno::with('usuario')->with('carrera')->get();
            return JsonResponse::success('Proceso exitoso', $alumnos);
        } catch (\Throwable $th) {
            return JsonResponse::error('Error al recuperar los alumnos', $th);
        }
    }

    public function show($id_alumno){
        try {
            $alumno=Alumno::with('usuario')->with('carrera')->where('id_alumno', $id_alumno)->first();
            if (!$alumno) {
                return JsonResponse::notFound('No se encontró el alumno');
            }
            return JsonResponse::success('Proceso exitoso', $alumno);
        } catch (\Throwable $th) {
            return JsonResponse::error('Error al recuperar el alumno', $th);
        }
    }
}
