<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class PasesOficialesController extends Controller
{
    public function index()
    {
        $resultados = session('pases_oficiales', []);
        return view('admin.pases.oficiales', compact('resultados'));
    }

public function generar(Request $request)
{
    $request->validate([
        'excel_personal' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        'excel_vacantes' => ['required', 'file', 'mimes:xlsx,xls,csv'],
    ]);

    $personalRows = $this->leerExcel($request->file('excel_personal'));
    $vacantesRows = $this->leerExcel($request->file('excel_vacantes'));

    $personal = $this->normalizarPersonalOficiales($personalRows);
    $vacantes = $this->normalizarVacantes($vacantesRows);

    if (empty($personal)) {
        return redirect()->route('pases.oficiales.index')
            ->with('error', 'El Excel de personal no tiene registros válidos.');
    }

    if (empty($vacantes)) {
        return redirect()->route('pases.oficiales.index')
            ->with('error', 'El Excel de vacantes no tiene registros válidos.');
    }

    $totalPersonal = count($personal);

    // ✅ Máximo 4 repeticiones por vacante (como pediste: 3 o 4)
    $repeticionesExtra = 4;

    // Slots base (cupos reales + 4 extras por vacante)
    $slotsBase = $this->expandirVacantesEnSlots($vacantes, $repeticionesExtra);

    if (empty($slotsBase)) {
        return redirect()->route('pases.oficiales.index')
            ->with('error', 'No se pudieron generar slots: revisa que nomenclatura_base no esté vacía.');
    }

    // ✅ Si slotsBase no alcanza para 328, reciclamos (repetimos la lista)
    $slots = $slotsBase;

    while (count($slots) < $totalPersonal) {
        $tmp = $slotsBase;
        shuffle($tmp);                  // cada ronda diferente
        $slots = array_merge($slots, $tmp);
        // seguridad extrema (no debería pasar en tu caso)
        if (count($slots) > $totalPersonal * 20) break;
    }

    // Aleatorio
    shuffle($personal);
    shuffle($slots);

    // ✅ garantizado: slots >= personal (por el while)
    $resultados = [];
    for ($i = 0; $i < $totalPersonal; $i++) {
        $p = $personal[$i];
        $s = $slots[$i];

        $resultados[] = [
            'ORD' => $p['ord'],
            'CEDULA' => $p['cedula'],
            'APELLIDOS' => $p['apellidos'],
            'NOMBRES' => $p['nombres'],
            'NOMENCLATURA_BASE' => $s['nomenclatura_base'],
            'FUNCION_EFECTIVA' => $s['funcion_efectiva'],
            'ESTADO_ORIGINAL' => $s['estado_original'],
            'ESTADO' => $s['estado_resultante'], // VACANTE / EXCEDIDO / YA EXCEDIDO / SE COMPLETÓ
        ];
    }

    // ✅ Seguridad de keys
    foreach ($resultados as &$row) {
        $row['ESTADO_ORIGINAL'] = $row['ESTADO_ORIGINAL'] ?? '-';
        $row['ESTADO'] = $row['ESTADO'] ?? '-';
    }
    unset($row);

    session()->put('pases_oficiales', $resultados);

    return redirect()->route('pases.oficiales.index')
        ->with('success', 'Pase generado correctamente. Total asignados: ' . count($resultados) . ' | Repeticiones extra (máx): ' . $repeticionesExtra);
}
    public function descargar()
    {
        $resultados = session('pases_oficiales', []);
        if (empty($resultados)) {
            return redirect()->route('pases.oficiales.index')->with('error', 'No hay datos para descargar.');
        }

        return Excel::download(new ArrayExport($resultados), 'pases_oficiales.xlsx');
    }

    // =========================================================
    // Helpers
    // =========================================================

    private function leerExcel($file): array
    {
        $data = Excel::toArray([], $file);
        return $data[0] ?? [];
    }

    private function normalizarPersonalOficiales(array $rows): array
    {
        if (empty($rows)) return [];

        $header = $this->mapHeader($rows[0]);
        $out = [];

        for ($i = 1; $i < count($rows); $i++) {
            $r = $rows[$i];

            $cedula = $this->getCell($r, $header, 'CEDULA');
            if (trim((string)$cedula) === '') continue;

            $ord = $this->getCell($r, $header, 'ORD');

            $ap1 = $this->getCell($r, $header, 'APELLIDO_1');
            $ap2 = $this->getCell($r, $header, 'APELLIDO_2');
            $n1  = $this->getCell($r, $header, 'NOMBRE_1');
            $n2  = $this->getCell($r, $header, 'NOMBRE_2');

            $out[] = [
                'ord' => trim((string)$ord),
                'cedula' => trim((string)$cedula),
                'apellidos' => Str::upper(trim((string)$ap1 . ' ' . (string)$ap2)),
                'nombres' => Str::upper(trim((string)$n1 . ' ' . (string)$n2)),
            ];
        }

        return $out;
    }

    private function normalizarVacantes(array $rows): array
    {
        if (empty($rows)) return [];

        $header = $this->mapHeader($rows[0]);
        $out = [];

        for ($i = 1; $i < count($rows); $i++) {
            $r = $rows[$i];

            $nom = $this->getCell($r, $header, 'NOMENCLATURA_BASE');
            if (trim((string)$nom) === '') continue;

            $func = $this->getCell($r, $header, 'FUNCION_EFECTIVA');
            $estado = $this->getCell($r, $header, 'ESTADO');
            $dif = $this->getCell($r, $header, 'DIFERENCIA');

            $out[] = [
                'nomenclatura_base' => Str::upper(trim((string)$nom)),
                'funcion_efectiva' => Str::upper(trim((string)$func)),
                'estado' => Str::upper(trim((string)$estado)),
                'diferencia' => (int)$dif,
            ];
        }

        return $out;
    }

    /**
     * Slots:
     * - diferencia > 0 => diferencia slots VACANTE + (repeticionesExtra) slots EXCEDIDO
     * - diferencia = 0 => 0 slots VACANTE + (repeticionesExtra) slots EXCEDIDO
     * - diferencia < 0 => 0 slots VACANTE + (repeticionesExtra) slots YA EXCEDIDO
     *
     * estado_original:
     * - dif < 0 o estado EXCEDIDO => YA EXCEDIDO
     * - dif == 0 => SE COMPLETÓ
     * - dif > 0 => VACANTE (o el estado del excel si viene)
     */
    private function expandirVacantesEnSlots(array $vacantes, int $repeticionesExtra = 3): array
    {
        $slots = [];

        foreach ($vacantes as $v) {
            $dif = (int)($v['diferencia'] ?? 0);
            $estadoExcel = Str::upper((string)($v['estado'] ?? ''));

            $nom = Str::upper((string)($v['nomenclatura_base'] ?? ''));
            $func = Str::upper((string)($v['funcion_efectiva'] ?? ''));

            if ($nom === '') continue;

            // Estado original legible
            if ($dif < 0 || $estadoExcel === 'EXCEDIDO') {
                $estadoOriginal = 'YA EXCEDIDO';
            } elseif ($dif === 0) {
                $estadoOriginal = 'SE COMPLETÓ';
            } else {
                $estadoOriginal = $estadoExcel !== '' ? $estadoExcel : 'VACANTE';
            }

            // Cupos reales (VACANTE) según diferencia
            $vacantesReales = max($dif, 0);
            for ($i = 0; $i < $vacantesReales; $i++) {
                $slots[] = [
                    'nomenclatura_base' => $nom,
                    'funcion_efectiva' => $func,
                    'estado_original' => $estadoOriginal,
                    'estado_resultante' => 'VACANTE',
                ];
            }

            // Cupos extra (para completar personal)
            for ($j = 0; $j < $repeticionesExtra; $j++) {
                $estadoRes = ($dif < 0 || $estadoExcel === 'EXCEDIDO')
                    ? 'YA EXCEDIDO'
                    : 'EXCEDIDO';

                $slots[] = [
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