<?php

namespace App\Http\Controllers;

use App\Models\Fichaje;
use App\Models\Usuario;
use App\Models\ResumenDiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FichajeController extends Controller
{
    /**
     * EMPLEADO → botón de fichar + su propio resumen
     */
    public function create()
    {
        $user = Auth::user();
        $hoy = Carbon::now()->toDateString();

        $resumen = ResumenDiario::where('user_id', $user->id)
            ->whereDate('fecha', $hoy)
            ->first();

        $ultimo = Fichaje::where('user_id', $user->id)
            ->orderBy('fecha_hora', 'desc')
            ->first();

        return view('fichajes.fichar', [
            'usuario' => $user,
            'ultimo'  => $ultimo,
            'resumen' => $resumen
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'tipo' => 'required|in:entrada,salida,pausa,reanudar'
        ]);

        Fichaje::create([
            'user_id'   => $user->id,
            'metodo_id' => 1, // web_app por defecto
            'tipo'      => $request->tipo,
            'fecha_hora' => now(),
        ]);

        // Recalcula resumen del día
        \App\Services\Resumen\ResumenService::recalcular(
            $user->id,
            now()->toDateString()
        );

        return back()->with('success', 'Movimiento registrado.');
    }
    /**
     * ENCARGADO / ADMIN → resumen general
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $fecha = $request->input('fecha', Carbon::now()->toDateString());

        // ADMIN → ve todas las empresas
        if ($user->rol->nombre === 'admin_sistema') {
            $empleados = Usuario::with('empresa', 'rol')->get();
        }

        // ENCARGADO → solo empleados de su empresa
        else {
            $empleados = Usuario::where('empresa_id', $user->empresa_id)
                ->with('empresa', 'rol')
                ->get();
        }

        // Resumenes ya calculados
        $resumenes = ResumenDiario::whereDate('fecha', $fecha)
            ->whereIn('user_id', $empleados->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('Fichajes.res_gnral', [
            'empleados'  => $empleados,
            'resumenes'  => $resumenes,
            'fecha'      => $fecha
        ]);
    }
}
