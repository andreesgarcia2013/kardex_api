<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use App\Models\Alumno;
use App\Models\Kardex;
use App\Validators\KardexValidator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KardexController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $rules = KardexValidator::rules();
            $request->validate($rules);

            $materias=$request->materias;
            $carga=[];
            foreach($materias as $materia){
                $kardex = Kardex::where('id_alumno', $request->id_alumno)->where('id_materia',$materia['id_materia'])->first();
                if (!$kardex) {
                    $carga[] = [
                        'id_alumno' => $request->id_alumno,
                        'id_materia' => $materia['id_materia'],
                        'calificacion' => $materia['calificacion'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            DB::table('kardex')->insert($carga);
            DB::commit();
            return JsonResponse::success('Materias cargadas con éxito', $carga);
        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponse::error('Error al cargar materias', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_alumno)
    {
        try {
            $kardex=Kardex::where('id_alumno', $id_alumno)->with('materia')->get();
            $sumaCalificaciones = 0;
            $cantidadMaterias = count($kardex);

            foreach ($kardex as $materia) {
                $sumaCalificaciones += $materia->calificacion;
            }

            $promedioCalificaciones = $cantidadMaterias > 0 ? $sumaCalificaciones / $cantidadMaterias : 0;
            $data=[
                'promedio'  =>$promedioCalificaciones,
                'kardex'    =>$kardex
            ];
            return JsonResponse::success('Proceso exitoso ', $data);
        } catch (\Throwable $th) {
            return JsonResponse::error('Error al recuperar el kardex', $th);
        }
    }

    public function pdfKardex($id_alumno)
    {
        try {
            $kardex=Kardex::where('id_alumno', $id_alumno)->with('materia')->get();
            $alumno=Alumno::with('usuario')->with('carrera')->where('id_alumno', $id_alumno)->first();

            $sumaCalificaciones = 0;
            $cantidadMaterias = count($kardex);

            foreach ($kardex as $materia) {
                $sumaCalificaciones += $materia->calificacion;
            }

            $promedioCalificaciones = $cantidadMaterias > 0 ? $sumaCalificaciones / $cantidadMaterias : 0;
            $data=[
                'alumno'    =>$alumno->usuario->nombre.' '.$alumno->usuario->apellido,
                'matricula' =>$alumno->matricula,
                'carrera'   =>$alumno->carrera->carrera,
                'grado'     =>$alumno->grado,
                'kardex'    =>$kardex,
                'promedio'  =>$promedioCalificaciones
            ];
            $pdf = PDF::loadView('pdf.kardex', $data);
            $nameFile = 'kardex-' . $alumno->matricula . '-' . now()->format('d-m-Y') . '.pdf';
            return $pdf->download($nameFile.'.pdf');
        } catch (\Throwable $th) {
            return JsonResponse::error('Error al recuperar el kardex', $th);
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
    public function update(Request $request, $id_alumno)
    {
        try {
            $materias=$request->materias;
            $materias = array_unique($materias->id_materia, SORT_REGULAR);
            $kardex=Kardex::where('id_alumno', $id_alumno)->whereIn('id_materia', $materias)->get();
            dd($kardex);

            foreach($kardex as $itemKardex){
                dd($itemKardex);
            }

        } catch (\Throwable $th) {
            return JsonResponse::error('Error al asignar el calificación', $th);
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
