<?php

namespace App\Exports;

use App\Models\Formulario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormulariosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Formulario::select(
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
            'celular'
        )->get();
    }

    public function headings(): array
    {
        return [
            'CÉDULA',
            'APELLIDO PATERNO',
            'APELLIDO MATERNO',
            'NOMBRES',
            'FECHA NACIMIENTO',
            'ESTADO CIVIL',
            'BANCO ID',
            'BANCO NOMBRE',
            'TIPO CUENTA',
            'CUENTA',
            'ESCUELA',
            'CELULAR',
        ];
    }
}