<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\AuditoriaApiController;
use App\Http\Controllers\Api\EmpresaApiController;
use App\Http\Controllers\Api\FichajeApiController;
use App\Http\Controllers\Api\MetodoFichajeApiController;
use App\Http\Controllers\Api\UsuarioApiController;

// LOGIN API -> OBTENER TOKEN

Route::post('/token', function (Request $request) {

    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'error' => 'Credenciales inválidas'
        ], 401);
    }

    $user = $request->user();

    $user->tokens()->delete();

    $token = $user->createToken('api')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user'  => $user,
    ]);
});

// RUTA PROTEGIDA DE PRUEBA

Route::middleware('auth:sanctum')->get('/ping', function (Request $request) {
    return response()->json([
        'ok'   => true,
        'user' => $request->user(),
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    // Listado paginado de auditoría
    Route::get('/auditorias', [AuditoriaApiController::class, 'index']);

    // Exportación de datos (sin PDF)
    Route::get('/auditorias/export', [AuditoriaApiController::class, 'exportData']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/empresas', [EmpresaApiController::class, 'index']);
    Route::post('/empresas', [EmpresaApiController::class, 'store']);
    Route::get('/empresas/{empresa}', [EmpresaApiController::class, 'show']);
    Route::put('/empresas/{empresa}', [EmpresaApiController::class, 'update']);
    Route::delete('/empresas/{empresa}', [EmpresaApiController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/fichar', [FichajeApiController::class, 'fichar']);

    Route::get('/mis-fichajes', [FichajeApiController::class, 'misFichajes']);

    Route::get('/resumen-general', [FichajeApiController::class, 'resumenGeneral']);

    Route::get('/estado-empresa', [FichajeApiController::class, 'estadoEmpresa']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/metodos-fichaje', [MetodoFichajeApiController::class, 'index']);
    Route::post('/metodos-fichaje', [MetodoFichajeApiController::class, 'store']);
    Route::get('/metodos-fichaje/{metodo}', [MetodoFichajeApiController::class, 'show']);
    Route::delete('/metodos-fichaje/{metodo}', [MetodoFichajeApiController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuarios', [UsuarioApiController::class, 'index']);
    Route::post('/usuarios', [UsuarioApiController::class, 'store']);
    Route::get('/usuarios/{usuario}', [UsuarioApiController::class, 'show']);
    Route::put('/usuarios/{usuario}', [UsuarioApiController::class, 'update']);
    Route::delete('/usuarios/{usuario}', [UsuarioApiController::class, 'destroy']);
});
