<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class PasesTecnicoController extends Controller
{
    public function index()
    {
        $resultados = session('pases_tecnico', []);
        return view('admin.pases.tecnico', compact('resultados'));
    }

    public function generar(Request $request)
{
    $request->validate([
        'excel_personal' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        'excel_vacantes' => ['required', 'file', 'mimes:xlsx,xls,csv'],
    ]);

    $personalRows = $this->leerExcel($request->file('excel_personal'));
    $vacantesRows = $this->leerExcel($request->file('excel_vacantes'));

    $personal = $this->normalizarPersonalTecnico($personalRows);
    $vacantes = $this->normalizarVacantesTecnico($vacantesRows);

    if (empty($personal)) {
        return redirect()->route('pases.tecnico.index')->with('error', 'El Excel de personal no tiene registros válidos.');
    }

    if (empty($vacantes)) {
        return redirect()->route('pases.tecnico.index')->with('error', 'El Excel de vacantes no tiene registros válidos.');
    }

    // ===============================
    // ✅ CALCULAR REPETICIONES EXTRA AUTOMÁTICAS
    // ===============================
    $totalPersonal = count($personal);

    $cuposReales = 0;
    foreach ($vacantes as $v) {
        $cuposReales += max((int)($v['diferencia'] ?? 0), 0);
    }

    $vacantesCount = count($vacantes);
    $faltante = max($totalPersonal - $cuposReales, 0);

    $repeticionesExtra = ($faltante > 0)
        ? (int) ceil($faltante / max($vacantesCount, 1))
        : 0;

    // (Opcional) mínimo 3:
    // $repeticionesExtra = max($repeticionesExtra, 3);

    $slots = $this->expandirVacantesEnSlots($vacantes, $repeticionesExtra);

    while (count($slots) < $totalPersonal) {
        $repeticionesExtra++;
        $slots = $this->expandirVacantesEnSlots($vacantes, $repeticionesExtra);
        if ($repeticionesExtra > 5000) break;
    }

    // Indexar slots por grado para intentar match por grado
    $byGrado = [];
    $pool = [];

    foreach ($slots as $s) {
        $g = $s['grado'] ?: 'SIN_GRADO';
        $byGrado[$g][] = $s;
        $pool[] = $s;
    }

    shuffle($personal);
    foreach ($byGrado as $g => $arr) {
        shuffle($arr);
        $byGrado[$g] = $arr;
    }
    shuffle($pool);

    $resultados = [];

    foreach ($personal as $p) {
        $g = $p['grado'] ?: 'SIN_GRADO';

        $slot = null;
        if (!empty($byGrado[$g])) {
            $slot = array_shift($byGrado[$g]);
        } elseif (!empty($pool)) {
            $slot = array_shift($pool);
        }

        if (!$slot) break;

        $resultados[] = [
            'CEDULA' => $p['cedula'],
            'GRADO' => $p['grado'],
            'APELLIDOS' => $p['apellidos'],
            'NOMBRES' => $p['nombres'],
            'HOJA_ORIGEN' => $p['hoja_origen'],
            'NOMENCLATURA_BASE' => $slot['nomenclatura_base'],
            'FUNCION_EFECTIVA' => $slot['funcion_efectiva'],
            'ESTADO_ORIGINAL' => $slot['estado_original'],
            'ESTADO' => $slot['estado_resultante'],
        ];
    }

    foreach ($resultados as &$row) {
        $row['ESTADO_ORIGINAL'] = $row['ESTADO_ORIGINAL'] ?? '-';
        $row['ESTADO'] = $row['ESTADO'] ?? '-';
    }
    unset($row);

    session()->put('pases_tecnico', $resultados);

    return redirect()->route('pases.tecnico.index')
        ->with('success', 'Pase generado correctamente. Total asignados: ' . count($resultados) . ' | Repeticiones extra usadas: ' . $repeticionesExtra);
}
    public function descargar()
    {
        $resultados = session('pases_tecnico', []);
        if (empty($resultados)) {
            return redirect()->route('pases.tecnico.index')->with('error', 'No hay datos para descargar.');
        }

        return Excel::download(new ArrayExport($resultados), 'pases_tecnico_operativo.xlsx');
    }

    // ======================
    // Helpers
    // ======================

    private function leerExcel($file): array
    {
        $data = Excel::toArray([], $file);
        return $data[0] ?? [];
    }

    private function normalizarPersonalTecnico(array $rows): array
    {
        if (empty($rows)) return [];
        $header = $this->mapHeader($rows[0]);

        $out = [];
        for ($i = 1; $i < count($rows); $i++) {
            $r = $rows[$i];
            $cedula = $this->getCell($r, $header, 'CEDULA');
            if (trim((string)$cedula) === '') continue;

            $grado = $this->getCell($r, $header, 'GRADO');
            $apellidos = $this->getCell($r, $header, 'APELLIDOS');
            $nombres = $this->getCell($r, $header, 'NOMBRES');
            $hoja = $this->getCell($r, $header, 'HOJA_ORIGEN');

            $out[] = [
                'cedula' => trim((string)$cedula),
                'grado' => Str::upper(trim((string)$grado)),
                'apellidos' => Str::upper(trim((string)$apellidos)),
                'nombres' => Str::upper(trim((string)$nombres)),
                'hoja_origen' => Str::upper(trim((string)$hoja)),
            ];
        }
        return $out;
    }

    private function normalizarVacantesTecnico(array $rows): array
    {
        if (empty($rows)) return [];
        $header = $this->mapHeader($rows[0]);

        $out = [];
        for ($i = 1; $i < count($rows); $i++) {
            $r = $rows[$i];

            $nom = $this->getCell($r, $header, 'NOMENCLATURA_BASE');
            if (trim((string)$nom) === '') continue;

            $grado = $this->getCell($r, $header, 'GRADO');
            $func = $this->getCell($r, $header, 'FUNCION_EFECTIVA');

            $estado = $this->getCell($r, $header, 'ESTADO');
            if ($estado === null) {
                $estado = $this->getCell($r, $header, 'ESTADOE'); // por si viene estadoe
            }

            $dif = $this->getCell($r, $header, 'DIFERENCIA');

            $out[] = [
                'nomenclatura_base' => Str::upper(trim((string)$nom)),
                'grado' => Str::upper(trim((string)$grado)),
                'funcion_efectiva' => Str::upper(trim((string)$func)),
                'estado' => Str::upper(trim((string)$estado)),
                'diferencia' => (int)$dif,
            ];
        }

        return $out;
    }

    private function expandirVacantesEnSlots(array $vacantes, int $repeticionesExtra = 3): array
    {
        $slots = [];

        foreach ($vacantes as $v) {
            $dif = (int)($v['diferencia'] ?? 0);
            $estadoExcel = Str::upper((string)($v['estado'] ?? ''));

            $nom = Str::upper((string)($v['nomenclatura_base'] ?? ''));
            $func = Str::upper((string)($v['funcion_efectiva'] ?? ''));
            $grado = Str::upper((string)($v['grado'] ?? ''));

            if ($nom === '') continue;

            if ($dif < 0 || $estadoExcel === 'EXCEDIDO') {
                $estadoOriginal = 'YA EXCEDIDO';
            } elseif ($dif === 0 && $estadoExcel !== 'VACANTE') {
                $estadoOriginal = 'SE COMPLETÓ';
            } else {
                $estadoOriginal = $estadoExcel !== '' ? $estadoExcel : ($dif > 0 ? 'VACANTE' : 'SE COMPLETÓ');
            }

            $vacantesReales = max($dif, 0);
            for ($i = 0; $i < $vacantesReales; $i++) {
                $slots[] = [
                    'grado' => $grado,
                    'nomenclatura_base' => $nom,
                    'funcion_efectiva' => $func,
                    'estado_original' => $estadoOriginal,
                    'estado_resultante' => 'VACANTE',
                ];
            }

            for ($j = 0; $j < $repeticionesExtra; $j++) {
                $estadoRes = ($dif < 0 || $estadoExcel === 'EXCEDIDO')
                    ? 'YA EXCEDIDO'
                    : 'EXCEDIDO';

                $slots[] = [
                    'grado' => $grado,
                    'nomenclatura_base' => $nom,
                    'funcion_efectiva' => $func,
                    'estado_original' => $estadoOriginal,
                    'estado_resultante' => $estadoRes,
                ];
            }
        }

        return $slots;
    }

    private function mapHeader(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $idx => $name) {
            $key = Str::upper(trim((string)$name));
            $map[$key] = $idx;
        }
        return $map;
    }

    private function getCell(array $row, array $header, string $colName)
    {
        $key = Str::upper($colName);
        if (!isset($header[$key])) return null;
        return $row[$header[$key]] ?? null;
    }
}