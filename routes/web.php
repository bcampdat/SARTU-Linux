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


// DASHBOARD

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


// AUTH (BREEZE)

require_once __DIR__ . '/auth.php';


// PERFIL

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// CAMBIO PASSWORD OBLIGATORIO

Route::middleware('auth')->group(function () {
    Route::get('/password/force-change', [CambioPassController::class, 'showForm'])
        ->name('password.force-change');

    Route::post('/password/force-change', [CambioPassController::class, 'update'])
        ->name('password.force-change.update');
});

// ADMIN SISTEMA

Route::middleware(['auth', 'rol:admin_sistema'])->group(function () {

    Route::resource('empresas', EmpresaController::class)->names([
        'index'   => 'empresa.index',
        'create'  => 'empresa.create',
        'store'   => 'empresa.store',
        'show'    => 'empresa.show',
        'edit'    => 'empresa.edit',
        'update'  => 'empresa.update',
        'destroy' => 'empresa.destroy',
    ]);
    Route::resource('usuarios', UsuarioController::class);

    Route::get('auditoria', [AuditoriaController::class, 'index'])
        ->name('auditoria.index');
});

// EMPLEADO + ENCARGADO

Route::middleware(['auth', 'rol:empleado,encargado'])->group(function () {

    // Fichar
    Route::get('fichajes/create', [FichajeController::class, 'create'])
        ->name('fichajes.create');

    Route::post('fichajes', [FichajeController::class, 'store'])
        ->name('fichajes.store');

    // Mi resumen personal
    Route::get('mi-resumen', [ResumenDiarioController::class, 'index'])
        ->name('fichaje.resumen');
});

// ENCARGADO

Route::middleware(['auth', 'rol:encargado'])->group(function () {

    // Empleados de su empresa
    Route::get('empleados', [UsuarioController::class, 'empleadosEmpresa'])
        ->name('encargado.empleados');

    // Fichajes empresa (TABLA ANTIGUA)
    Route::get('fichajes', [FichajeController::class, 'index'])
        ->name('fichajes.index');

    // Resumen empresa (TABLA ANTIGUA)
    Route::get('empleados/resumen', [FichajeController::class, 'index'])
        ->name('empleados.resumen');
});

// ENCARGADO + ADMIN SISTEMA

Route::middleware(['auth', 'rol:admin_sistema,encargado'])->group(function () {

    // ESTADO TIEMPO REAL EMPRESA
    Route::get('empresa/estado', [FichajeController::class, 'estadoEmpresa'])
        ->name('empresa.estado');

    // RESUMEN DASHBOARD EMPRESA (NUEVO)
    Route::get('empresa/resumen', [FichajeController::class, 'resumenEmpresa'])
        ->name('empresa.resumen');
});


// AUDITORIA

Route::middleware(['auth'])->group(function () {
    Route::get('/auditoria', [AuditoriaController::class, 'index'])
        ->name('auditoria.index');

    Route::get('/auditoria/pdf', [AuditoriaController::class, 'exportPdf'])
        ->name('auditoria.export.pdf');
});
