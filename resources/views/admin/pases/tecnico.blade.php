@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Módulo Técnico Operativo - Generar Pase</h1>

    <a href="{{ route('pases.tecnico.descargar') }}"
       class="px-4 py-2 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
      Descargar Excel
    </a>
  </div>

  @if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">
      {{ session('error') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">
      <ul class="list-disc ml-5 text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('pases.tecnico.generar') }}" method="POST" enctype="multipart/form-data"
        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-6">
    @csrf

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Excel Personal (CEDULA, GRADO, APELLIDOS, NOMBRES, HOJA_ORIGEN)
        </label>
        <input name="excel_personal" type="file" required
               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50
                      dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 focus:outline-none">
      </div>

      <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Excel Vacantes (nomenclatura_base, grado, funcion_efectiva, diferencia, estado/estadoe)
        </label>
        <input name="excel_vacantes" type="file" required
               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50
                      dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 focus:outline-none">
      </div>
    </div>

    <button class="mt-5 px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200">
      Generar Pase
    </button>
  </form>

  <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Resultado</h2>

    <div class="relative overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-3 py-2">CEDULA</th>
            <th class="px-3 py-2">GRADO</th>
            <th class="px-3 py-2">APELLIDOS</th>
            <th class="px-3 py-2">NOMBRES</th>
            <th class="px-3 py-2">HOJA_ORIGEN</th>
            <th class="px-3 py-2">NOMENCLATURA_BASE</th>
            <th class="px-3 py-2">FUNCION_EFECTIVA</th>
            <th class="px-3 py-2">ESTADO_ORIGINAL</th>
            <th class="px-3 py-2">ESTADO</th>
          </tr>
        </thead>
        <tbody>
          @forelse($resultados as $r)
            <tr class="border-t border-gray-200 dark:border-gray-700">
              <td class="px-3 py-2 font-semibold">{{ $r['CEDULA'] }}</td>
              <td class="px-3 py-2">{{ $r['GRADO'] }}</td>
              <td class="px-3 py-2">{{ $r['APELLIDOS'] }}</td>
              <td class="px-3 py-2">{{ $r['NOMBRES'] }}</td>
              <td class="px-3 py-2">{{ $r['HOJA_ORIGEN'] }}</td>
              <td class="px-3 py-2">{{ $r['NOMENCLATURA_BASE'] }}</td>
              <td class="px-3 py-2">{{ $r['FUNCION_EFECTIVA'] }}</td>
              <td class="px-3 py-2">{{ $r['ESTADO_ORIGINAL'] ?? '-' }}</td>
<td class="px-3 py-2">{{ $r['ESTADO'] ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="9" class="px-3 py-6 text-center text-gray-500">Sin datos todavía</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection