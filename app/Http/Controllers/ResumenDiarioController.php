<?php

namespace App\Http\Controllers;

use App\Models\ResumenDiario;
use App\Models\Fichaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumenDiarioController extends Controller
{
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
