@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
    <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
        Importar Servidores (Excel)
    </h1>

    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
        El Excel debe tener encabezados: <b>cedula</b>, <b>apellidos</b>, <b>nombres</b>
    </p>

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

    <form method="POST" action="{{ route('servidores.import') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Archivo Excel</label>
            <input type="file" name="archivo" required
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50
                          dark:text-gray-200 dark:bg-gray-700 dark:border-gray-600">
        </div>

        <button class="w-full text-white bg-blue-600 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5">
            Importar
        </button>
    </form>

    <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
        <div class="font-semibold mb-1">Ejemplo de Excel:</div>
        <div>cedula | apellidos | nombres</div>
        <div>1723456789 | LASRA GARCIA | ALEX DAVID</div>
    </div>
</div>
@endsection