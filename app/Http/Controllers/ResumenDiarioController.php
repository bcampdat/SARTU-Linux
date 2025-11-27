<?php

namespace App\Http\Controllers;

use App\Models\ResumenDiario;
use App\Models\Fichaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * @OA\Tag(
 *   name="ResumenDiario",
 *   description="Resúmenes diarios y listados (vistas web)"
 * )
 */
class ResumenDiarioController extends Controller
{
    /**
     * @OA\Get(
     *   path="/resumen-diario",
     *   tags={"ResumenDiario"},
     *   summary="Ver resúmenes diarios (vista)",
     *   @OA\Parameter(
     *     name="fecha",
     *     in="query",
     *     description="Fecha del resumen (YYYY-MM-DD). Por defecto, hoy.",
     *     @OA\Schema(type="string", format="date", example="2024-06-15")
     *   ),
     *   @OA\Response(response=200, description="HTML view")
     * )
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $fecha = $request->input('fecha', now()->toDateString());

        // Si es ENCARGADO → ve todos los resúmenes de su empresa
        if ($user->rol->nombre === 'encargado') {

            $resumenes = ResumenDiario::whereDate('fecha', $fecha)
                ->whereHas('usuario', function ($q) use ($user) {
                    $q->where('empresa_id', $user->empresa_id);
                })
                ->with('usuario')
                ->get();

            // Empleados de la empresa
            $empleados = $resumenes->pluck('usuario')->unique('id');

            return view('resumen.res_gnral', [
                'resumenes' => $resumenes->keyBy('user_id'),
                'empleados' => $empleados,
                'fecha' => $fecha
            ]);
        }

        // EMPLEADO NORMAL → solo su resumen
        $resumen = ResumenDiario::where('user_id', $user->id)
            ->whereDate('fecha', $fecha)
            ->first();

        $fichajes = Fichaje::where('user_id', $user->id)
            ->whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora')
            ->get();

        return view('resumen.resumen', [
            'resumen'  => $resumen,
            'fichajes' => $fichajes,
            'fecha'    => $fecha
        ]);
    }
}
