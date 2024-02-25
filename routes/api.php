<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);


        Route::group(['middleware' => ['auth:sanctum', 'role:2']], function() {
            Route::prefix('alumno')-> group(function() {
                Route::get('/kardex-pdf/{id_alumno}', [KardexController::class, 'pdfKardex']);
                Route::get('/kardex/{id_alumno}', [KardexController::class, 'show']);
            });
            
        });

        Route::group(['middleware' => ['auth:sanctum', 'role:1']], function() {
            Route::prefix('manager')-> group(function() {
                Route::prefix('admin') -> controller(UserController::class) -> group(function() {
                    Route::get('/admins', 'index');
                    Route::get('/admin/{id_user}', 'show');
                    Route::post('/register', 'store');
                    Route::patch('/update/{id_user}', 'update');
                });
    
                Route::prefix('alumno') -> controller(AlumnoController::class) -> group(function() {
                    Route::get('/alumnos', 'index');
                    Route::get('/alumno/{id_alumno}', 'show');
                    Route::post('/register', 'storeAlumno');
                    Route::patch('/update/{id_alumno}', 'update');
                });
    
                    
                Route::prefix('materia') -> controller(MateriaController::class) -> group(function() {
                    Route::get('/materias', 'index');
                    Route::get('/materia/{id_materia}', 'show');
                    Route::post('/register', 'store');
                    Route::patch('/update/{id_materia}', 'update');
                });
    
                Route::prefix('kardex') -> controller(KardexController::class) -> group(function() {
                    Route::post('/register', 'store');
                    Route::put('/calificar/{id_alumno}', 'update');
                    Route::get('/kardex/{id_alumno}', 'show');
                    Route::get('/kardex-pdf/{id_alumno}', 'pdfKardex');
                }); 

                Route::prefix('carreras') -> controller(CarreraController::class) -> group(function() {
                    Route::get('/', 'index');
                });
            });


        });
    

    

    
        Route::put('/restore', [UserController::class, 'restorePassword']);
        
    

});

// Route::prefix('v1/university')-> group(function() {

//     //MANAGER
//     Route::prefix('admin') -> controller(UserController::class) -> group(function() {
//         Route::get('/admins', 'index');
//         Route::get('/admin/{id_user}', 'show');
//         Route::post('/register', 'store');
//         Route::patch('/update/{id_user}', 'update');
//     });

//     Route::prefix('alumno') -> controller(AlumnoController::class) -> group(function() {
//         Route::get('/alumnos', 'index');
//         Route::get('/alumno/{id_alumno}', 'show');
//         Route::post('/register', 'storeAlumno');
//         Route::patch('/update/{id_alumno}', 'update');
//     });

//     Route::prefix('materia') -> controller(MateriaController::class) -> group(function() {
//         Route::get('/materias', 'index');
//         Route::get('/materia/{id_materia}', 'show');
//         Route::post('/register', 'store');
//         Route::patch('/update/{id_materia}', 'update');
//     });

//     Route::prefix('kardex') -> controller(KardexController::class) -> group(function() {
//         Route::post('/register', 'store');
//         Route::put('/calificar/{id_alumno}', 'update');
//         Route::get('/kardex/{id_alumno}', 'show');
//     }); 

//     Route::put('/restore', [UserController::class, 'restorePassword']);
//     Route::get('/kardex-pdf/{id_alumno}', [KardexController::class, 'pdfKardex']);

// });
