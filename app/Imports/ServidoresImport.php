<?php

namespace App\Imports;

use App\Models\Servidor;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ServidoresImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        // Acepta encabezados: cedula, apellidos, nombres
        $cedula = preg_replace('/\D+/', '', (string)($row['cedula'] ?? ''));

        $apellidos = trim((string)($row['apellidos'] ?? ''));
        $nombres   = trim((string)($row['nombres'] ?? ''));

        // Saltar filas inválidas
        if (strlen($cedula) !== 10 || $apellidos === '' || $nombres === '') {
            return null;
        }

        // Evitar duplicados: si existe, actualiza
        Servidor::updateOrCreate(
            ['cedula' => $cedula],
            [
                'apellidos' => Str::upper($apellidos),
                'nombres'   => Str::upper($nombres),
            ]
        );

        // Retornar null porque ya guardamos con updateOrCreate
        return null;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}