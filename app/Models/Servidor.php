<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    protected $table = 'servidores';   // ✅ tabla real

    // Si la bd "nomina" es OTRA conexión, mira el punto 2 y agrega $connection

    protected $fillable = ['cedula', 'apellidos', 'nombres'];
}