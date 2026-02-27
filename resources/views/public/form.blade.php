@extends('layouts.app')

@section('content')
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

    <form method="POST" action="{{ route('form.store') }}" class="space-y-6">
        @csrf

        {{-- CÉDULA --}}
       {{-- CÉDULA --}}
<div>
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        Cédula
    </label>

    <div class="flex gap-2">
        <input id="cedula" name="cedula" maxlength="10"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white"
               placeholder="Ingrese cédula">

        <button type="button"
                id="btn_validar"
                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white">
            Validar
        </button>
    </div>

    <p id="cedula_msg" class="text-sm mt-2 text-gray-300"></p>
</div>
        {{-- APELLIDOS Y NOMBRES --}}
        <div class="grid grid-cols-3 gap-4 mt-4">
    <div>
        <label>Apellido paterno</label>
        <input id="apellido_paterno" name="apellido_paterno"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white">
    </div>

    <div>
        <label>Apellido materno</label>
        <input id="apellido_materno" name="apellido_materno"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white">
    </div>

    <div>
        <label>Nombres</label>
        <input id="nombres" name="nombres"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white">
    </div>
</div>
        {{-- FECHA NACIMIENTO --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha nacimiento</label>
            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                          focus:ring-blue-500 focus:border-blue-500 block w-full
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>

        {{-- ESTADO CIVIL --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estado civil</label>
            <select name="estado_civil"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 block w-full
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione</option>
                @foreach($estados as $e)
                    <option value="{{ $e }}" @selected(old('estado_civil') === $e)>{{ $e }}</option>
                @endforeach
            </select>
        </div>

        {{-- BANCO --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Banco</label>
            <select name="banco_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 block w-full
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione</option>
                @foreach($bancos as $id => $nombre)
                    <option value="{{ $id }}" @selected((string)old('banco_id') === (string)$id)>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- TIPO CUENTA --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de cuenta</label>
            <select name="tipo_cuenta"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 block w-full
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione</option>
                <option value="AHORROS" @selected(old('tipo_cuenta')==='AHORROS')>AHORROS</option>
                <option value="CORRIENTE" @selected(old('tipo_cuenta')==='CORRIENTE')>CORRIENTE</option>
            </select>
        </div>

        {{-- CUENTA --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cuenta</label>
            <input name="cuenta" value="{{ old('cuenta') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                          focus:ring-blue-500 focus:border-blue-500 block w-full
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>

        {{-- ESCUELA (tu select actual aquí) --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Escuela</label>
            <select name="escuela"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 block w-full
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione</option>
                @for($i=1; $i<=5; $i++)
                    <option value="{{ $i }}" @selected((string)old('escuela')===(string)$i)>{{ $i }}</option>
                @endfor
            </select>
        </div>

        {{-- CELULAR --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Celular</label>
            <input name="celular" maxlength="10" value="{{ old('celular') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                          focus:ring-blue-500 focus:border-blue-500 block w-full
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>

        <button class="w-full text-white bg-blue-600 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5">
            Guardar
        </button>
    </form>
</div>

{{-- ✅ SCRIPT: autollenar por cédula --}}
<script>
document.getElementById('btn_validar').addEventListener('click', async function () {

    const cedula = document.getElementById('cedula').value.trim();
    const msg = document.getElementById('cedula_msg');
    const apPat = document.getElementById('apellido_paterno');
    const apMat = document.getElementById('apellido_materno');
    const nombres = document.getElementById('nombres');

    apPat.value = '';
    apMat.value = '';
    nombres.value = '';

    if (cedula.length !== 10 || !/^\d+$/.test(cedula)) {
        msg.innerHTML = '<span class="text-red-400">Debe ingresar 10 dígitos</span>';
        return;
    }

    msg.innerHTML = '<span class="text-yellow-400">Buscando...</span>';

    try {
        const response = await fetch(`/form/lookup-cedula/${cedula}`, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await response.json();

        if (!response.ok) {
            msg.innerHTML = `<span class="text-red-400">${data.message ?? 'Error'}</span>`;
            console.log('ERROR BACKEND:', data);
            return;
        }

        apPat.value   = data.data.apellido_paterno ?? '';
        apMat.value   = data.data.apellido_materno ?? '';
        nombres.value = data.data.nombres ?? '';

        msg.innerHTML = '<span class="text-green-400">Encontrado ✅</span>';

    } catch (error) {
        msg.innerHTML = '<span class="text-red-400">Error de red (no backend)</span>';
        console.error(error);
    }
});
</script>
@endsection