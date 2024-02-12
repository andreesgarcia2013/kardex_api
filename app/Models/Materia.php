<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_usuario';
    protected $table = 'usuarios';

    protected $fillable = [
        'codigo',
        'materia',
        'grado',
        'calificacion_minima'
    ];
}
