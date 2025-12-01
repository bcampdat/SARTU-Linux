<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Fichaje\FichajeFactory;
use App\Models\Fichaje;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\ResumenDiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\AuditoriaService;

class FichajeApiController extends Controller
{
  
    public function fichar(Request $request)
    {
        $user = Auth::user();

        if ($user->estado === 'bloqueado') {
            return response()->json(['error' => 'Cuenta bloqueada'], 403);
        }

        if ($user->estado === 'pendiente') {
            return response()->json(['error' => 'Cuenta pendiente de activación'], 403);
        }

        $request->validate([
            'tipo'   => 'required|in:entrada,salida,pausa,reanudar',
            'metodo' => 'nullable|string|in:web,pwa,nfc,pc',
            'lat'    => 'required|numeric',
            'lng'    => 'required|numeric',
            'notas'  => 'nullable|string|max:500',
        ]);

        $metodo = $request->input('metodo', 'api');

        $cacheKey = 'fichaje_metodo_activo_' . $user->id;
        $metodoActivo = cache()->get($cacheKey);

        if ($metodoActivo && $metodoActivo !== $metodo) {
            return response()->json([
                'error' => 'Debes finalizar con el mismo método'
            ], 409);
        }

        try {
            $strategy = FichajeFactory::make($metodo);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Método inválido'], 422);
        }

        $datos = $request->only(['tipo', 'lat', 'lng', 'notas']);

        $strategy->fichar($user, $datos);

        if (in_array($datos['tipo'], ['entrada', 'reanudar'])) {
            cache()->put($cacheKey, $metodo, now()->addHours(24));
        }

        if ($datos['tipo'] === 'salida') {
            cache()->forget($cacheKey);
        }

        AuditoriaService::log(
            'fichaje_api_' . $datos['tipo'],
            'Fichaje',
            null,
            null,
            [
                'user_id' => $user->id,
                'tipo'    => $datos['tipo'],
                'metodo'  => $metodo,
                'lat'     => $datos['lat'],
                'lng'     => $datos['lng'],
                'notas'   => $datos['notas'] ?? null,
            ],
            'Fichaje realizado por API'
        );

        \App\Services\Resumen\ResumenService::recalcular(
            $user->id,
            now()->toDateString()
        );

        return response()->json([
            'ok' => true,
            'tipo' => $datos['tipo'],
            'fecha_hora' => now()
        ], 201);
    }

    // 
    public function misFichajes(Request $request)
    {
        $user = Auth::user();

        $fecha = $request->input('fecha', now()->toDateString());

        $fichajes = Fichaje::where('user_id', $user->id)
            ->whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora')
            ->get();

        $resumen = ResumenDiario::where('user_id', $user->id)
            ->whereDate('fecha', $fecha)
            ->first();

        return response()->json([
            'resumen' => $resumen,
            'fichajes' => $fichajes
        ]);
    }

   
    public function resumenGeneral(Request $request)
    {
        $user = Auth::user();

        $fecha = $request->input('fecha', Carbon::today()->toDateString());

        $empleados = $user->rol->nombre === 'admin_sistema'
            ? Usuario::with(['empresa', 'rol'])->get()
            : Usuario::where('empresa_id', $user->empresa_id)
                ->with(['empresa', 'rol'])
                ->get();

        $resumenes = ResumenDiario::whereDate('fecha', $fecha)
            ->whereIn('user_id', $empleados->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return response()->json([
            'fecha' => $fecha,
            'empleados' => $empleados,
            'resumenes' => $resumenes
        ]);
    }

    public function estadoEmpresa(Request $request)
    {
        $user = Auth::user();

        $empresaId = $user->rol->nombre === 'admin_sistema'
            ? $request->empresa_id
            : $user->empresa_id;

        $empleados = Usuario::where('empresa_id', $empresaId)->get();

        $anio = $request->input('anio', now()->year);

        foreach ($empleados as $emp) {
            $ultimo = $emp->fichajes()->latest('fecha_hora')->first();
            $emp->estado_actual = $ultimo->tipo ?? 'salida';
            $emp->hora_estado   = $ultimo?->fecha_hora;

            $emp->minutos_anio = ResumenDiario::where('user_id', $emp->id)
                ->whereYear('fecha', $anio)
                ->sum('tiempo_trabajado');
        }

        return response()->json([
            'empresa_id' => $empresaId,
            'empleados' => $empleados
        ]);
    }
}
