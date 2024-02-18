<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use App\Validators\MateriaValidator;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $materias=Materia::get();
            return JsonResponse::success('Proceso exitoso', $materias);
        } catch (\Throwable $th) {
            return JsonResponse::error('Error al recuperar las materias', $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
            $rules = MateriaValidator::rules();
            $request->validate($rules);

            $materia = new Materia();

            $materia -> codigo              = $request -> codigo;
            $materia -> materia             = $request -> materia;
            $materia -> grado               = $request -> grado;
            $materia -> calificacion_minima = $request -> calificacion_minima;

            $materia -> save();

            DB::commit();
            return JsonResponse::success('Proceso exitoso', $materia);
        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al registrar la materia', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_materia)
    {
        try {
            $materia=Materia::find($id_materia);
            if (!$materia) {
                return JsonResponse::notFound('No se encontró la materia');
            }
            return JsonResponse::success('Proceso exitoso', $materia);
        } catch (\Throwable $th) {
            return JsonResponse::error('Error al recuperar las materias', $th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_materia)
    {
        try {
            DB::beginTransaction();
            $rules = MateriaValidator::updateRules();
            $request->validate($rules);

            $materia=Materia::find($id_materia);

            $materia->update($request->all());

            DB::commit();
            return JsonResponse::success('Proceso exitoso', $materia);
        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al actualizar la materia', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
