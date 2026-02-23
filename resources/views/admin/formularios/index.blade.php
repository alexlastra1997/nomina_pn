@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
    <div class="flex items-center justify-between gap-3 mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Registros</h1>

        <form method="GET" class="flex gap-2">
            <input name="q" value="{{ $q }}"
                   class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                   placeholder="Buscar cédula o nombre...">
            <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Buscar</button>
        </form>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-3 py-2">Cédula</th>
                    <th class="px-3 py-2">Apellidos y nombres</th>
                    <th class="px-3 py-2">F. Nac</th>
                    <th class="px-3 py-2">Estado civil</th>
                    <th class="px-3 py-2">Banco/Coop</th>
                    <th class="px-3 py-2">Tipo</th>
                    <th class="px-3 py-2">Cuenta</th>
                    <th class="px-3 py-2">Escuela</th>
                    <th class="px-3 py-2">Celular</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="px-3 py-2 font-semibold">{{ $it->cedula }}</td>
                        <td class="px-3 py-2">
                            {{ $it->apellido_paterno }} {{ $it->apellido_materno }} {{ $it->nombres }}
                        </td>
                        <td class="px-3 py-2">{{ \Carbon\Carbon::parse($it->fecha_nacimiento)->format('d/m/Y') }}</td>
                        <td class="px-3 py-2">{{ $it->estado_civil }}</td>
                        <td class="px-3 py-2">{{ $it->banco_id }} - {{ $it->banco_nombre }}</td>
                        <td class="px-3 py-2">{{ $it->tipo_cuenta }}</td>
                        <td class="px-3 py-2">{{ $it->cuenta }}</td>
                        <td class="px-3 py-2">{{ $it->escuela }}</td>
                        <td class="px-3 py-2">{{ $it->celular }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-3 py-6 text-center text-gray-500">Sin registros</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
@endsection