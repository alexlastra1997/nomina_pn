@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Formulario público</h1>
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Ingrese los datos. Al guardar aparecerá “Formulario completado”.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">
            <ul class="list-disc ml-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('form.store') }}"
          class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- CEDULA --}}
            <div>
                <label for="cedula" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cédula</label>
                <input id="cedula" name="cedula" value="{{ old('cedula') }}" maxlength="10"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                              dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                              placeholder-gray-400"
                       placeholder="0102030405">
            </div>

            {{-- FECHA NACIMIENTO --}}
            <div>
                <label for="fecha_nacimiento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha nacimiento</label>
                <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                              dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            {{-- APELLIDO PATERNO --}}
            <div>
                <label for="apellido_paterno" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellido paterno</label>
                <input id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                              dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                              placeholder-gray-400"
                       placeholder="Ej: PEREZ">
            </div>

            {{-- APELLIDO MATERNO --}}
            <div>
                <label for="apellido_materno" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellido materno</label>
                <input id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                              dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                              placeholder-gray-400"
                       placeholder="Ej: LOPEZ">
            </div>

            {{-- NOMBRES --}}
            <div class="md:col-span-2">
                <label for="nombres" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombres</label>
                <input id="nombres" name="nombres" value="{{ old('nombres') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                              dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                              placeholder-gray-400"
                       placeholder="Ej: JUAN CARLOS">
            </div>

            {{-- ESTADO CIVIL (SELECT) --}}
            <div>
                <label for="estado_civil" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estado civil</label>
                <select id="estado_civil" name="estado_civil"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccione</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado }}" @selected(old('estado_civil') === $estado)>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ESCUELA (1..5) --}}
            <div>
                <label for="escuela" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Escuela</label>
                <select id="escuela" name="escuela"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccione</option>
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}" @selected((string)old('escuela') === (string)$i)>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            {{-- BANCO --}}
            <div class="md:col-span-2">
                <label for="banco_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Banco y/o Cooperativa</label>
                <select id="banco_id" name="banco_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccione</option>
                    @foreach($bancos as $id => $nombre)
                        <option value="{{ $id }}" @selected((string)old('banco_id') === (string)$id)>
                            {{ $id }} - {{ $nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TIPO CUENTA --}}
            <div>
                <label for="tipo_cuenta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo cuenta</label>
                <select id="tipo_cuenta" name="tipo_cuenta"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccione</option>
                    <option value="AHORROS" @selected(old('tipo_cuenta') === 'AHORROS')>AHORROS</option>
                    <option value="CORRIENTE" @selected(old('tipo_cuenta') === 'CORRIENTE')>CORRIENTE</option>
                </select>
            </div>

            {{-- CUENTA --}}
            <div>
                <label for="cuenta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cuenta</label>
                <input id="cuenta" name="cuenta" value="{{ old('cuenta') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                              dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                              placeholder-gray-400"
                       placeholder="Número de cuenta">
            </div>

            {{-- CELULAR --}}
            <div class="md:col-span-2">
                <label for="celular" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de celular</label>
                <input id="celular" name="celular" value="{{ old('celular') }}" maxlength="10"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full
                              dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                              placeholder-gray-400"
                       placeholder="0999999999">
            </div>

        </div>

        <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
            Guardar
        </button>
    </form>
</div>
@endsection