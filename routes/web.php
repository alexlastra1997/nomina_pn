<?php

use App\Http\Controllers\AdminFormularioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasesOficialesController;
use App\Http\Controllers\PasesTecnicoController;
use App\Http\Controllers\PublicFormularioController;
use App\Http\Controllers\ServidorImportController;
use Illuminate\Support\Facades\Route;

// FORMULARIO PUBLICO (SIN LOGIN)
Route::get('/', [PublicFormularioController::class, 'create'])->name('form.create');
// Form público
Route::get('/form', [PublicFormularioController::class, 'create'])->name('form.create');
Route::post('/form', [PublicFormularioController::class, 'store'])->name('form.store');

Route::get('/form/lookup-cedula/{cedula}', 
    [PublicFormularioController::class, 'lookupCedula']
)->name('form.lookupCedula');

// AUTH (SIN BREEZE)
Route::get('/registro', [AuthController::class, 'registerForm'])->name('auth.registerForm');
Route::post('/registro', [AuthController::class, 'register'])->name('auth.register');

Route::get('/login', [AuthController::class, 'loginForm'])->name('auth.loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// ADMIN (SOLO LOGUEADOS)
Route::middleware('sessionauth')->group(function () {
    Route::get('/admin/formularios', [AdminFormularioController::class, 'index'])->name('admin.form.index');

        Route::get('/admin/pases/oficiales', [PasesOficialesController::class, 'index'])->name('pases.oficiales.index');
    Route::post('/admin/pases/oficiales/generar', [PasesOficialesController::class, 'generar'])->name('pases.oficiales.generar');
    Route::get('/admin/pases/oficiales/descargar', [PasesOficialesController::class, 'descargar'])->name('pases.oficiales.descargar');

    // TECNICO OPERATIVO
    Route::get('/admin/pases/tecnico', [PasesTecnicoController::class, 'index'])->name('pases.tecnico.index');
    Route::post('/admin/pases/tecnico/generar', [PasesTecnicoController::class, 'generar'])->name('pases.tecnico.generar');
    Route::get('/admin/pases/tecnico/descargar', [PasesTecnicoController::class, 'descargar'])->name('pases.tecnico.descargar');

    Route::get('/servidores/importar', [ServidorImportController::class, 'form'])->name('servidores.import.form');
Route::post('/servidores/importar', [ServidorImportController::class, 'import'])->name('servidores.import');
});