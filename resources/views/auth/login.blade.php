@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
    <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Iniciar sesión</h1>

    <form method="POST" action="{{ route('auth.login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input type="password" name="password"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        <button class="w-full text-white bg-blue-600 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5">
            Entrar
        </button>
    </form>
</div>
@endsection