<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FichajeController;
use App\Http\Controllers\AuditoriaController;

// Dashboard (Breeze)
Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require_once __DIR__.'/auth.php';

// Rutas para ADMIN SISTEMA
Route::middleware(['auth', 'rol:admin_sistema'])->group(function () {
    Route::resource('empresas', EmpresaController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
});

// Rutas para ENCARGADO
Route::middleware(['auth', 'rol:encargado'])->group(function () {
    Route::resource('fichajes', FichajeController::class)->only(['index']);
});

