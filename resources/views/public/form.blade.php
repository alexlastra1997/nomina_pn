@extends('layouts.app')

@section('content')
<style>
    /* Placeholders menos visibles */
    input::placeholder { opacity: .35; }
</style>

<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">

    <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Formulario Público</h1>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="publicForm" method="POST" action="{{ route('form.store') }}" class="space-y-6">
        @csrf

        {{-- CÉDULA --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Cédula
            </label>

            <div class="flex gap-2">
                <input id="cedula" name="cedula" maxlength="10"
                       class="w-full p-2 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm
                              dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Ingrese cédula"
                       inputmode="numeric">

                <button type="button"
                        id="btn_validar"
                        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white text-sm font-medium">
                    Validar
                </button>
            </div>

            <p id="cedula_msg" class="text-sm mt-2 text-gray-500 dark:text-gray-300"></p>
        </div>

        {{-- APELLIDOS Y NOMBRES --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellido paterno</label>
                <input id="apellido_paterno" name="apellido_paterno"
                       class="w-full p-2 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm
                              dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Apellido paterno">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellido materno</label>
                <input id="apellido_materno" name="apellido_materno"
                       class="w-full p-2 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm
                              dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Apellido materno">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombres</label>
                <input id="nombres" name="nombres"
                       class="w-full p-2 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm
                              dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Nombres">
            </div>
        </div>

        {{-- FECHA NACIMIENTO --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha nacimiento</label>
            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                          focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>

        {{-- ESTADO CIVIL --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estado civil</label>
            <select name="estado_civil" id="estado_civil"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione</option>
                @foreach($estados as $e)
                    <option value="{{ $e }}" @selected(old('estado_civil') === $e)>{{ $e }}</option>
                @endforeach
            </select>
        </div>

        {{-- BANCO (Flowbite dropdown con buscador adentro) --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Banco</label>

            {{-- Valor real para el POST --}}
            <input type="hidden" name="banco_id" id="banco_id" value="{{ old('banco_id') }}">

            <div class="relative">
                <button id="bancoDropdownButton" type="button"
                        class="w-full flex items-center justify-between bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                               focus:ring-blue-500 focus:border-blue-500 p-2.5
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <span id="bancoSelectedText" class="truncate">
                        {{ old('banco_id') ? ($bancos[(int)old('banco_id')] ?? 'Seleccione') : 'Seleccione' }}
                    </span>
                    <svg class="w-4 h-4 ml-2 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="bancoDropdown"
                     class="hidden absolute z-50 mt-2 w-full bg-white rounded-lg shadow border border-gray-200
                            dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                        <input id="bancoSearchInside" type="text" placeholder="Buscar banco..."
                               class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                      focus:ring-blue-500 focus:border-blue-500 p-2
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <ul id="bancoList"
                        class="max-h-64 overflow-y-auto p-1 text-sm text-gray-700 dark:text-gray-200">
                        @foreach($bancos as $id => $nombre)
                            <li>
                                <button type="button"
                                        class="w-full text-left px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 banco-option"
                                        data-id="{{ $id }}"
                                        data-text="{{ $nombre }}">
                                    <span class="block truncate">{{ $nombre }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @error('banco_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- TIPO CUENTA --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de cuenta</label>
            <select name="tipo_cuenta" id="tipo_cuenta"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione</option>
                <option value="AHORROS" @selected(old('tipo_cuenta')==='AHORROS')>AHORROS</option>
                <option value="CORRIENTE" @selected(old('tipo_cuenta')==='CORRIENTE')>CORRIENTE</option>
            </select>
        </div>

        {{-- CUENTA --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cuenta</label>
            <input name="cuenta" id="cuenta" value="{{ old('cuenta') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                          focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Número de cuenta">
        </div>

        {{-- ESCUELA (NUEVAS OPCIONES) --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Escuela</label>
            <select name="escuela" id="escuela"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione</option>

                @php
                  $escuelasList = [
                    'ESP. Escuela Superior de Policia "Gral. Alberto Henriquez Gallo"',
                    'EFP. Sgop. José Emilio Castillo Solís (Tambillo)',
                    'EFP. Chambo',
                    'EFP. Sbos. Gerardo Ramos Basantes (San Miguel)',
                    'EFP. Cbos. Froilán Jiménez Granda (Cuenca)',
                    'EFP. Unidad de Equitación y Remonta',
                    'EFP. Cbos. José Luis Mejía Solórzano (Gustavo Noboa)',
                    'EFP. Cbos. Fermín Eulogio Álava Álava - Chone',
                    'EFP. Santo Domingo',
                    'EFP. Cbos.. Fabián Alberto Armijos Jiménez (Atahualpa)',
                    'EFP. Cbos. José Lizandro Herrera Calderón (Fumisa)',
                    'EFP. Cbos. Víctor Hugo Usca Pachacama (Chimbo)',
                    'EFP. Catamayo',
                    'EFP. UPMA',
                    'EFP. San Diego',
                  ];
                @endphp

                @foreach($escuelasList as $esc)
                    <option value="{{ $esc }}" @selected(old('escuela')===$esc)>{{ $esc }}</option>
                @endforeach
            </select>
        </div>

        {{-- CELULAR --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Celular</label>
            <input name="celular" id="celular" maxlength="10" value="{{ old('celular') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                          focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Número de celular"
                   inputmode="numeric">
        </div>

        <button id="btn_guardar" class="w-full text-white bg-blue-600 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5">
            Guardar
        </button>
    </form>
</div>

{{-- ✅ SCRIPT --}}
<script>
(function() {
    const btnValidar = document.getElementById('btn_validar');
    const cedulaInput = document.getElementById('cedula');
    const msg = document.getElementById('cedula_msg');

    const apPat = document.getElementById('apellido_paterno');
    const apMat = document.getElementById('apellido_materno');
    const nombres = document.getElementById('nombres');
    const cuenta = document.getElementById('cuenta');

    const form = document.getElementById('publicForm');
    const btnGuardar = document.getElementById('btn_guardar');

    // Bloquea/desbloquea todo menos cédula y validar
    function disableForm(disabled) {
        const elements = form.querySelectorAll('input, select, button');
        elements.forEach(el => {
            if (el.id === 'cedula' || el.id === 'btn_validar') return;
            el.disabled = disabled;
            el.classList.toggle('opacity-60', disabled);
            el.classList.toggle('cursor-not-allowed', disabled);
        });
        if (btnGuardar) btnGuardar.disabled = disabled;
    }

    // Mayúsculas en vivo
    function forceUpper(el) {
        if (!el) return;
        el.addEventListener('input', () => el.value = (el.value || '').toUpperCase());
    }
    forceUpper(apPat);
    forceUpper(apMat);
    forceUpper(nombres);
    forceUpper(cuenta);

    // VALIDAR CÉDULA + bloquear si ya existe
    btnValidar.addEventListener('click', async function () {
        const cedula = (cedulaInput.value || '').trim();

        apPat.value = '';
        apMat.value = '';
        nombres.value = '';

        if (cedula.length !== 10 || !/^\d+$/.test(cedula)) {
            msg.innerHTML = '<span class="text-red-500">Debe ingresar 10 dígitos</span>';
            disableForm(false);
            return;
        }

        msg.innerHTML = '<span class="text-yellow-500">Buscando...</span>';
        disableForm(false);

        try {
            const response = await fetch(`/form/lookup-cedula/${cedula}`, {
                headers: { 'Accept': 'application/json' }
            });

            const data = await response.json().catch(() => ({}));

            if (response.status === 409) {
                msg.innerHTML = `<span class="text-red-500">${data.message ?? 'Esta cédula ya registró el formulario.'}</span>`;
                disableForm(true);
                return;
            }

            if (!response.ok) {
                msg.innerHTML = `<span class="text-red-500">${data.message ?? 'Error'}</span>`;
                console.log('ERROR BACKEND:', data);
                disableForm(false);
                return;
            }

            apPat.value   = (data.data?.apellido_paterno ?? '').toUpperCase();
            apMat.value   = (data.data?.apellido_materno ?? '').toUpperCase();
            nombres.value = (data.data?.nombres ?? '').toUpperCase();

            msg.innerHTML = '<span class="text-green-500">Encontrado ✅</span>';

        } catch (error) {
            msg.innerHTML = '<span class="text-red-500">Error de red</span>';
            console.error(error);
            disableForm(false);
        }
    });

    // ========= BANCO: dropdown con buscador adentro =========
    const btn = document.getElementById('bancoDropdownButton');
    const dd  = document.getElementById('bancoDropdown');
    const search = document.getElementById('bancoSearchInside');
    const list = document.getElementById('bancoList');
    const hidden = document.getElementById('banco_id');
    const selectedText = document.getElementById('bancoSelectedText');

    if (btn && dd && search && list && hidden && selectedText) {
        const options = Array.from(list.querySelectorAll('.banco-option'));

        function openDD() {
            dd.classList.remove('hidden');
            setTimeout(() => {
                search.focus();
                search.select();
            }, 0);
        }

        function closeDD() {
            dd.classList.add('hidden');
            search.value = '';
            options.forEach(o => o.closest('li').classList.remove('hidden'));
        }

        function toggleDD() {
            if (dd.classList.contains('hidden')) openDD();
            else closeDD();
        }

        btn.addEventListener('click', toggleDD);

        search.addEventListener('input', () => {
            const q = search.value.toLowerCase().trim();
            options.forEach(o => {
                const txt = (o.dataset.text || '').toLowerCase();
                const li = o.closest('li');
                if (!q || txt.includes(q)) li.classList.remove('hidden');
                else li.classList.add('hidden');
            });
        });

        options.forEach(o => {
            o.addEventListener('click', () => {
                hidden.value = o.dataset.id || '';
                selectedText.textContent = o.dataset.text || 'Seleccione';
                closeDD();
            });
        });

        document.addEventListener('click', (e) => {
            if (!dd.classList.contains('hidden')) {
                const inside = dd.contains(e.target) || btn.contains(e.target);
                if (!inside) closeDD();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !dd.classList.contains('hidden')) closeDD();
        });
    }

    // Por defecto: no bloquear
    disableForm(false);
})();
</script>
@endsection