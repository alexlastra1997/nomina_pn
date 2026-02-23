<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $fillable = [
        'cedula',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'fecha_nacimiento',
        'estado_civil',
        'banco_id',
        'banco_nombre',
        'tipo_cuenta',
        'cuenta',
        'escuela',
        'celular',
    ];
}