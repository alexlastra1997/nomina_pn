<?php

use App\Http\Controllers\AdminFormularioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicFormularioController;
use Illuminate\Support\Facades\Route;

// FORMULARIO PUBLICO (SIN LOGIN)
Route::get('/', [PublicFormularioController::class, 'create'])->name('form.create');
Route::post('/formulario', [PublicFormularioController::class, 'store'])->name('form.store');

// AUTH (SIN BREEZE)
Route::get('/login', [AuthController::class, 'loginForm'])->name('auth.loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::get('/register', [AuthController::class, 'registerForm'])->name('auth.registerForm');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// ADMIN (SOLO LOGUEADOS)
Route::middleware('sessionauth')->group(function () {
    Route::get('/admin/formularios', [AdminFormularioController::class, 'index'])->name('admin.form.index');
});