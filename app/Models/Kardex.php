<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kardex extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_kardex';
    protected $table = 'kardex';

    protected $fillable = [
        'id_alumno',
        'id_materia',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}
