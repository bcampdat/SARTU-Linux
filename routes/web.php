<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CambioPassController;
use App\Http\Controllers\FichajeController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResumenDiarioController;


// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


// Breeze login / logout / reset

require_once __DIR__.'/auth.php';


// Perfil de usuario

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// Cambio obligatorio de contraseña

Route::middleware('auth')->group(function () {
    Route::get('/password/force-change', [CambioPassController::class, 'showForm'])
        ->name('password.force-change');
    Route::post('/password/force-change', [CambioPassController::class, 'update'])
        ->name('password.force-change.update');
});


// ADMIN

Route::middleware(['auth', 'rol:admin_sistema'])->group(function () {
    Route::resource('empresas', EmpresaController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::get('auditoria', [AuditoriaController::class, 'index'])
        ->name('auditoria.index');
});


// EMPLEADO + ENCARGADO (fichar / ver su resumen)

Route::middleware(['auth', 'rol:empleado,encargado'])->group(function () {
    Route::get('fichajes/create', [FichajeController::class, 'create'])
        ->name('fichajes.create');

    Route::post('fichajes', [FichajeController::class, 'store'])
        ->name('fichajes.store');

    Route::get('mi-resumen', [FichajeController::class, 'resumen'])
        ->name('fichaje.resumen');

    Route::get('mi-resumen', [ResumenDiarioController::class, 'index'])
        ->name('fichaje.resumen');
});


// ENCARGADO (gestión de su empresa)

Route::middleware(['auth', 'rol:encargado'])->group(function () {

    // Vista SOLO lectura de usuarios de su empresa
    Route::get('empleados', [UsuarioController::class, 'empleadosEmpresa'])
        ->name('encargado.empleados');

    // Fichajes de todos los empleados de su empresa
    Route::get('fichajes', [FichajeController::class, 'index'])
        ->name('fichajes.index');

    // Resumen diario de empresa
    Route::get('empleados/resumen', [FichajeController::class, 'index'])
        ->name('empleados.resumen');
});
