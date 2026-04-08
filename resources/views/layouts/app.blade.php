<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Formulario') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900">

<nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('form.create') }}" class="text-lg font-bold text-gray-900 dark:text-white">
            FORMULARIO
        </a>

        <div class="flex items-center gap-3">
            @if(session('auth_ok'))
                <a href="{{ route('admin.form.index') }}"
                   class="text-sm px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Ver registros
                </a>

                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button class="text-sm px-3 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-white">
                        Salir
                    </button>
                </form>
            @else
                <a href=""
                   class="text-sm px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Login
                </a>
                <a href=""
                   class="text-sm px-3 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-white">
                    Register
                </a>
            @endif
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto p-4">
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

    @yield('content')
</main>

</body>
</html>