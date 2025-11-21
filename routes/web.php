<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FichajeController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// Dashboard (Breeze)
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Rutas de autenticación (Breeze)
require_once __DIR__.'/auth.php'; // NOSONAR

// Rutas de perfil accesibles para cualquier usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Rutas para ADMIN
Route::middleware(['auth', 'rol:admin_sistema'])->group(function () {
    Route::resource('empresas', EmpresaController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
});

// Rutas para Gestor
Route::middleware(['auth', 'rol:encargado'])->group(function () {
    Route::resource('fichajes', FichajeController::class)->only(['index']);
});
