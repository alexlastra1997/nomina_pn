<?php

namespace App\Http\Controllers;

use App\Models\Formulario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicFormularioController extends Controller
{
    private function bancos(): array
    {
        return [
            1 => 'BCO. AMAZONAS',
            114 => 'BCO. BANECUADOR',
            2 => 'BCO. BOLIVARIANO',
            76 => 'BCO. CAPITAL SA',
            3 => 'BCO. CENTROMUNDO',
            4 => 'BCO. CITYBANK',
            6 => 'BCO. COMERCIAL DE MANABI',
            8 => 'BCO. DE GUAYAQUIL',
            10 => 'BCO. DE LOJA',
            11 => 'BCO. DEL AUSTRO',
            12 => 'BCO. DEL LITORAL',
            13 => 'BCO. DEL PACIFICO',
            99 => 'BCO. DELBANK S.A.',
            102 => 'BCO. FINCA S.A.',
            15 => 'BCO. GENERAL RUMINAHUI',
            16 => 'BCO. INTERNACIONAL',
            19 => 'BCO. MACHALA',
            18 => 'BCO. MM JARAMILLO ARTEAGA',
            20 => 'BCO. PICHINCHA',
            57 => 'BCO. PROCREDIT',
            144 => 'BCO. PRODUBANCO(BCO. PRODUCCION)',
            21 => 'BCO. SOLIDARIO',
            23 => 'BCO. TERRITORIAL',
            24 => 'BCO. UNIBANCO',
            143 => 'BCO. VISIONFUND ECUADOR',
            136 => 'COOP. 13 DE ABRIL',
            70 => 'COOP. 15 DE ABRIL PORTOVIEJO',
            137 => 'COOP. 16 DE JULIO',
            27 => 'COOP. 23 DE JULIO',
            28 => 'COOP. 29 DE OCTUBRE',
            85 => 'COOP. 4 DE OCTUBRE LTDA.',
            63 => 'COOP. 9 DE OCTUBRE LTDA',
            141 => 'COOP. ALFONSO JARAMILLO',
            139 => 'COOP. ALFONSO JARAMILLO LC',
            48 => 'COOP. ALIANZA DEL VALLE LTDA.',
            122 => 'COOP. ALIANZA MINAS LTDA',
            84 => 'COOP. AMBATO LTDA',
            29 => 'COOP. ANDALUCIA',
            125 => 'COOP. ANDINA LTDA.',
            44 => 'COOP. ATUNTAQUI LTDA.',
            124 => 'COOP. CACEC',
            61 => 'COOP. CACPE BIBLIAN TDA',
            93 => 'COOP. CACPE CELICA',
            133 => 'COOP. CACPE GUALAQUIZA',
            128 => 'COOP. CACPE LOJA',
            121 => 'COOP. CACPE PALORA',
            131 => 'COOP. CACPE YANTZAZA',
            111 => 'COOP. CACPE ZAMORA',
            25 => 'COOP. CACPECO',
            69 => 'COOP. CALCETA LTDA',
            59 => 'COOP. CAMARA COMERCIO DE QUITO',
            80 => 'COOP. CAMARA DE COMERCIO DE AMBATO',
            94 => 'COOP. CHIBULEO LIMITADA',
            49 => 'COOP. CHONE LTDA.',
            81 => 'COOP. CODESARROLLO',
            116 => 'COOP. COMERCIO',
            45 => 'COOP. COMERCIO LTDA PORTOV',
            135 => 'COOP. CORPORACION CENTRO',
            30 => 'COOP. COTOCOLLAO',
            129 => 'COOP. CRECER WINARI LTDA',
            26 => 'COOP. DE LA PEQ. EMPRESA PASTAZA',
            31 => 'COOP. DESARROLLO PUEBLOS',
            107 => 'COOP. EDUC.DE CHIMBORAZO',
            127 => 'COOP. EDUCAD TULCAN CACET',
            106 => 'COOP. EDUCADORES DE ZAMORA',
            32 => 'COOP. EL SAGRARIO',
            97 => 'COOP. FERNANDO DAQUILEMA',
            33 => 'COOP. GUARANDA LTDA.',
            108 => 'COOP. HUAQUILLAS LTDA',
            82 => 'COOP. JARDIN AZUAYO LTDA',
            71 => 'COOP. JESUS DEL GRAN PODER',
            115 => 'COOP. JUAN PIO DE MORA',
            60 => 'COOP. JUV.ECUAT.PROGRESISTA LTDA',
            145 => 'COOP. KULLKI WASI TLDA.',
            110 => 'COOP. LA  BENEFICA',
            46 => 'COOP. LA DOLOROSA LTDA',
            83 => 'COOP. LA MERCED LTDA',
            112 => 'COOP. LA NUEVA JERUSALEN',
            118 => 'COOP. LUCHA CAMPESINA',
            101 => 'COOP. LUZ DEL VALLE',
            34 => 'COOP. M. ESTEBAN GODOY ORTEGA',
            100 => 'COOP. MANANTIAL DE ORO LTDA.',
            119 => 'COOP. MAQUITA CUSHUNCHIC',
            104 => 'COOP. MINGA LTDA.',
            126 => 'COOP. MUJERES UNIDAS CACMU',
            89 => 'COOP. MUSHUC RUNA LTDA.',
            35 => 'COOP. NACIONAL',
            109 => 'COOP. NUEVA ESPERANZA LTDA',
            98 => 'COOP. NUEVA HUANCALVILCA',
            66 => 'COOP. ONCE DE JUNIO',
            123 => 'COOP. ORDEN Y SEGURIDAD',
            36 => 'COOP. OSCUS',
            37 => 'COOP. PABLO MUNOZ VEGA',
            65 => 'COOP. PADRE JULIAN LORENTE',
            103 => 'COOP. PEDRO MONCAYO',
            91 => 'COOP. PILAHUIN',
            90 => 'COOP. PILAHUIN TIO',
            78 => 'COOP. POLICIA NACIONAL LTDA',
            47 => 'COOP. PREVISION AHORRO Y DES.',
            38 => 'COOP. PROGRESO',
            95 => 'COOP. PUELLARO',
            132 => 'COOP. PUERTO LOPEZ',
            39 => 'COOP. RIOBAMBA',
            142 => 'COOP. SAC',
            105 => 'COOP. SAN ANTONIO LTDA.',
            40 => 'COOP. SAN FRANCISCO',
            77 => 'COOP. SAN GABRIEL LTDA',
            62 => 'COOP. SAN JOSE',
            140 => 'COOP. SAN MIGUEL LTDA',
            67 => 'COOP. SANTA ROSA LTDA',
            41 => 'COOP. SERFIN',
            130 => 'COOP. SIERRA CENTRO',
            138 => 'COOP. SIMON BOLIVAR',
            72 => 'COOP. SN FRANCISCO DE ASIS LTDA.',
            120 => 'COOP. TENA LTDA',
            42 => 'COOP. TULCAN',
            134 => 'COOP. UNIBLOCK Y SERV.LTDA',
            43 => 'COOP. UNIDAD FAMILIAR',
            96 => 'COOP. UNION EL EJIDO',
            92 => 'COOP. VIRGEN DEL CISNE',
            50 => 'MUTL. AMBATO',
            51 => 'MUTL. AZUAY',
            52 => 'MUTL. BENALCAZAR',
            53 => 'MUTL. IMBABURA',
            54 => 'MUTL. LUIS VARGAS TORRES',
            55 => 'MUTL. PICHINCHA',
        ];
    }

    private function estadosCiviles(): array
    {
        return [
            'SOLTERO/A',
            'CASADO/A',
            'VIUDO/A',
            'UNION LIBRE',
            'DIVORCIADO/A',
        ];
    }

    public function create()
    {
        return view('public.form', [
            'bancos' => $this->bancos(),
            'estados' => $this->estadosCiviles(),
        ]);
    }

    /**
     * Validación matemática de cédula ecuatoriana (personas naturales)
     */
    private function cedulaEcuadorValida(string $cedula): bool
    {
        if (!preg_match('/^\d{10}$/', $cedula)) return false;

        $provincia = (int) substr($cedula, 0, 2);
        $tercer = (int) $cedula[2];

        if ($provincia < 1 || $provincia > 24) return false;
        if ($tercer < 0 || $tercer > 5) return false;

        $digitos = array_map('intval', str_split($cedula));
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $v = $digitos[$i];
            if ($i % 2 === 0) {
                $v *= 2;
                if ($v > 9) $v -= 9;
            }
            $suma += $v;
        }

        $verificador = (10 - ($suma % 10)) % 10;
        return $verificador === $digitos[9];
    }

    /**
     * AJAX: Busca en BD nomina -> tabla servidores por cédula
     * Devuelve apellido_paterno, apellido_materno, nombres
     */
    public function lookupCedula(string $cedula)
{
    try {
        $cedula = preg_replace('/\D+/', '', $cedula);

        if (strlen($cedula) !== 10) {
            return response()->json([
                'ok' => false,
                'type' => 'invalid',
                'message' => 'La cédula debe tener 10 dígitos'
            ], 422);
        }

        if (!$this->cedulaEcuadorValida($cedula)) {
            return response()->json([
                'ok' => false,
                'type' => 'invalid',
                'message' => 'Cédula inválida'
            ], 422);
        }

        // ✅ Probar conexión antes (si falla, sabrás que es BD)
        DB::connection('nomina_pn')->getPdo();

        // ✅ Consulta a nomina.servidores
        $s = DB::connection('nomina_pn')
            ->table('servidores')
            ->select(['cedula', 'apellidos', 'nombres'])
            ->whereRaw("REPLACE(TRIM(cedula),' ','') = ?", [$cedula])
            ->first();

        if (!$s) {
            return response()->json([
                'ok' => false,
                'type' => 'not_found',
                'message' => 'No encontrado en nómina'
            ], 404);
        }

        $apellidos = trim(preg_replace('/\s+/', ' ', (string)($s->apellidos ?? '')));
        $nombres   = trim(preg_replace('/\s+/', ' ', (string)($s->nombres ?? '')));

        $parts = $apellidos === '' ? [] : explode(' ', $apellidos);

        if (count($parts) >= 2) {
            $apellidoMaterno = array_pop($parts);
            $apellidoPaterno = implode(' ', $parts);
        } else {
            $apellidoPaterno = $parts[0] ?? '';
            $apellidoMaterno = '';
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'cedula' => $cedula,
                'apellido_paterno' => $apellidoPaterno,
                'apellido_materno' => $apellidoMaterno,
                'nombres' => $nombres,
            ]
        ]);

    } catch (\Throwable $e) {
        // ✅ Te devuelve el error real en JSON para depurar (solo mientras pruebas)
        return response()->json([
            'ok' => false,
            'type' => 'server_error',
            'message' => 'Error interno: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
}

    public function store(Request $request)
    {
        $bancos = $this->bancos();
        $estados = $this->estadosCiviles();

        $data = $request->validate([
            'cedula' => ['required','digits:10','unique:formularios,cedula'],
            'apellido_paterno' => ['required','string','max:80'],
            'apellido_materno' => ['required','string','max:80'],
            'nombres' => ['required','string','max:120'],
            'fecha_nacimiento' => ['required','date','before:today'],

            'estado_civil' => ['required', Rule::in($estados)],

            'banco_id' => ['required','integer', Rule::in(array_keys($bancos))],
            'tipo_cuenta' => ['required', Rule::in(['AHORROS','CORRIENTE'])],
            'cuenta' => ['required','string','max:30'],

            // Si ya cambiaste escuelas a texto, cambia esta validación
            'escuela' => ['required'], // ✅ deja así para no romper por tipos

            'celular' => ['required','digits:10'],
        ]);

        $data['apellido_paterno'] = Str::upper(trim($data['apellido_paterno']));
        $data['apellido_materno'] = Str::upper(trim($data['apellido_materno']));
        $data['nombres'] = Str::upper(trim($data['nombres']));
        $data['estado_civil'] = Str::upper(trim($data['estado_civil']));
        $data['tipo_cuenta'] = Str::upper(trim($data['tipo_cuenta']));
        $data['cuenta'] = Str::upper(trim($data['cuenta']));

        $data['banco_nombre'] = Str::upper($bancos[(int)$data['banco_id']]);

        Formulario::create($data);

        return redirect()->route('form.create')->with('success', 'Formulario completado.');
    }
}