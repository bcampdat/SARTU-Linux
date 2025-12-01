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
        $hoy  = now()->toDateString();

        $empleadosQuery = Usuario::where('empresa_id', $user->empresa_id);

        $empleadosTotal         = $empleadosQuery->count();
        $totalUsuariosActivos   = $empleadosQuery->clone()->where('activo', 1)->count();
        $totalUsuariosInactivos = $empleadosQuery->clone()->where('activo', 0)->count();

        $fichajesEmpresaQuery = Fichaje::whereHas('usuario', function ($q) use ($user) {
            $q->where('empresa_id', $user->empresa_id);
        });

        $totalEntradasHoy = $fichajesEmpresaQuery
            ->clone()
            ->whereDate('fecha_hora', $hoy)
            ->where('tipo', 'entrada')
            ->count();

        $totalSalidasHoy = $fichajesEmpresaQuery
            ->clone()
            ->whereDate('fecha_hora', $hoy)
            ->where('tipo', 'salida')
            ->count();


        $resumenEmpresaHoy = ResumenDiario::whereDate('fecha', $hoy)
            ->whereHas('usuario', function ($q) use ($user) {
                $q->where('empresa_id', $user->empresa_id);
            })
            ->get();

        $ultimosFichajesEmpresa = Fichaje::whereDate('fecha_hora', $hoy)
            ->whereHas('usuario', function ($q) use ($user) {
                $q->where('empresa_id', $user->empresa_id);
            })
            ->with('usuario')
            ->latest('fecha_hora')
            ->get()
            ->groupBy('user_id')
            ->take(5);


        $miResumen = ResumenDiario::where('user_id', $user->id)
            ->whereDate('fecha', $hoy)
            ->first();

        $misFichajesHoy = Fichaje::where('user_id', $user->id)
            ->whereDate('fecha_hora', $hoy)
            ->get();

        $misUltimosFichajes = Fichaje::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'vista' => '_dashEncargado',
            'data'  => [
                // EMPRESA
                'empleadosTotal'     => $empleadosTotal,
                'totalUsuariosActivos' => $totalUsuariosActivos,
                'totalUsuariosInactivos' => $totalUsuariosInactivos,
                'totalEntradasHoy'   => $totalEntradasHoy,
                'totalSalidasHoy'    => $totalSalidasHoy,
                'ultimosFichajesEmpresa' => $ultimosFichajesEmpresa,
                'tiempoTrabajadoTotalEmpresa' => $resumenEmpresaHoy->sum('tiempo_trabajado'),
                'tiempoPausasTotalEmpresa'    => $resumenEmpresaHoy->sum('tiempo_pausas'),

                // PERSONALES DEL ENCARGADO
                'miResumen'          => $miResumen,
                'misUltimosFichajes' => $misUltimosFichajes,
                'misFichajesHoy'     => $misFichajesHoy
            ]
        ]);
    }

    //  DASHBOARD EMPLEADO

    private function dashboardEmpleado(Request $request)
    {
        $user = Auth::user();
        $hoy = now()->toDateString();

        // FICHAJES DEL DÍA ORDENADOS
        $fichajesHoy = Fichaje::where('user_id', $user->id)
            ->whereDate('fecha_hora', $hoy)
            ->orderBy('fecha_hora')
            ->get();

        // ÚLTIMO FICHAJE REAL (ESTADO ACTUAL)
        $ultimoFichaje = Fichaje::where('user_id', $user->id)
            ->latest('fecha_hora')
            ->first();

        // RESUMEN DEL DÍA
        $resumen = ResumenDiario::where('user_id', $user->id)
            ->whereDate('fecha', $hoy)
            ->first();

        // HISTÓRICO RECIENTE
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
                'ultimoFichaje'    => $ultimoFichaje
            ]
        ]);
    }
}
