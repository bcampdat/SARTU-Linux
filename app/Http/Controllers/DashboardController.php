<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Fichaje;
use App\Models\ResumenDiario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        switch ($user->rol->nombre) {

            case 'admin_sistema':
                return $this->dashboardAdmin($request);

            case 'encargado':
                return $this->dashboardEncargado($request);

            case 'empleado':
                return $this->dashboardEmpleado($request);

            default:
                abort(403);
        }
    }

 
    //  DASHBOARD ADMIN
  
    private function dashboardAdmin(Request $request)
    {
        $hoy = now()->toDateString();

        $totalUsuariosActivos   = Usuario::where('activo', 1)->count();
        $totalUsuariosInactivos = Usuario::where('activo', 0)->count();

        $totalEntradasHoy = Fichaje::whereDate('fecha_hora', $hoy)->where('tipo', 'entrada')->count();
        $totalSalidasHoy  = Fichaje::whereDate('fecha_hora', $hoy)->where('tipo', 'salida')->count();

        $resumenHoy = ResumenDiario::whereDate('fecha', $hoy)->get();

        $ultimosFichajes = Fichaje::with('usuario')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', [
            'vista' => '_dashAdmin',
            'data' => [
                'totalUsuariosActivos'   => $totalUsuariosActivos,
                'totalUsuariosInactivos' => $totalUsuariosInactivos,
                'totalEntradasHoy'       => $totalEntradasHoy,
                'totalSalidasHoy'        => $totalSalidasHoy,
                'tiempoTrabajadoTotal'   => $resumenHoy->sum('tiempo_trabajado'),
                'tiempoPausasTotal'      => $resumenHoy->sum('tiempo_pausas'),
                'ultimosFichajes'        => $ultimosFichajes
            ]
        ]);
    }

   
    //  DASHBOARD ENCARGADO
    
    private function dashboardEncargado(Request $request)
    {
        $user = Auth::user();
        $hoy = now()->toDateString();

        $empleados = Usuario::where('empresa_id', $user->empresa_id);

        $totalUsuariosActivos   = $empleados->where('activo', 1)->count();
        $totalUsuariosInactivos = (clone $empleados)->where('activo', 0)->count();

        $fichajesHoy = Fichaje::whereHas('usuario', function ($q) use ($user) {
            $q->where('empresa_id', $user->empresa_id);
        });

        $totalEntradasHoy = (clone $fichajesHoy)->where('tipo', 'entrada')->count();
        $totalSalidasHoy  = (clone $fichajesHoy)->where('tipo', 'salida')->count();

        $resumenHoy = ResumenDiario::whereHas('usuario', function ($q) use ($user) {
            $q->where('empresa_id', $user->empresa_id);
        })->whereDate('fecha', $hoy)->get();

        $ultimosFichajes = Fichaje::whereHas('usuario', function ($q) use ($user) {
            $q->where('empresa_id', $user->empresa_id);
        })->with('usuario')
          ->latest()
          ->take(8)
          ->get();

        return view('dashboard', [
            'vista' => '_dashEncargado',
            'data' => [
                'empleadosTotal'         => $empleados->count(),
                'totalUsuariosActivos'   => $totalUsuariosActivos,
                'totalUsuariosInactivos' => $totalUsuariosInactivos,
                'totalEntradasHoy'       => $totalEntradasHoy,
                'totalSalidasHoy'        => $totalSalidasHoy,
                'tiempoTrabajadoTotal'   => $resumenHoy->sum('tiempo_trabajado'),
                'tiempoPausasTotal'      => $resumenHoy->sum('tiempo_pausas'),
                'ultimosFichajes'        => $ultimosFichajes
            ]
        ]);
    }

    //  DASHBOARD EMPLEADO
  
    private function dashboardEmpleado(Request $request)
    {
        $user = Auth::user();
        $hoy = now()->toDateString();

        $fichajesHoy = Fichaje::where('user_id', $user->id)
            ->whereDate('fecha_hora', $hoy)
            ->get();

        $resumen = ResumenDiario::where('user_id', $user->id)
            ->whereDate('fecha', $hoy)
            ->first();
        
        $ultimosFichajes = Fichaje::where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', [
            'vista' => '_dashEmpleado',
            'data' => [
                'resumen'          => $resumen,
                'ultimosFichajes'  => $ultimosFichajes,
                'fichajesHoy'      => $fichajesHoy,
                'estado'           => $user->estado
            ]
        ]);
    }
}

