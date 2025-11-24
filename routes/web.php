<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CambioPassController;
use App\Http\Controllers\FichajeController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Breeze login / logout / forgot password...
require_once __DIR__.'/auth.php';

// Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Cambio obligatorio password
Route::middleware(['auth'])->group(function () {
    Route::get('/password/force-change', [CambioPassController::class, 'showForm'])
        ->name('password.force-change');

    Route::post('/password/force-change', [CambioPassController::class, 'update'])
        ->name('password.force-change.update');
});

// ADMIN
Route::middleware(['auth', 'rol:admin_sistema'])->group(function () {
    Route::resource('empresas', EmpresaController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
});

// ENCARGADO
Route::middleware(['auth', 'rol:encargado'])->group(function () {
    Route::resource('fichajes', FichajeController::class)->only(['index']);
});

Route::middleware(['auth', 'rol:encargado'])->group(function () {
    Route::resource('fichajes', FichajeController::class)->only(['create', 'store']);
});

// EMPLEADO
Route::middleware(['auth', 'rol:empleado'])->group(function () {
    Route::resource('fichajes', FichajeController::class)->only(['create', 'store']);
});

Route::get('/fichaje/resumen', [FichajeController::class, 'resumen'])
    ->middleware(['auth', 'rol:empleado'])
    ->name('fichaje.resumen');

Route::middleware(['auth','rol:admin_sistema,encargado'])
    ->get('/empleados/resumen', [FichajeController::class, 'resumenGeneral'])
    ->name('empleados.resumen');

Route::get('/empleados/resumen', [FichajeController::class, 'resumenGeneral'])
      ->middleware(['auth','role:admin_sistema,encargado'])
      ->name('empleados.resumen');

