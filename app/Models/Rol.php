<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_rol';
    protected $table = 'roles';

    protected $fillable = [
        'rol'
    ];
}
