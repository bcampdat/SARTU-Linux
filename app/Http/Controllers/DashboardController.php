<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Fichaje;
use App\Models\ResumenDiario;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Usuario logueado

        // Usuarios de la misma empresa
        $usuarios = Usuario::where('id_empresa', $user->id_empresa);

        $totalUsuariosActivos = $usuarios->where('activo', 1)->count();
        $totalUsuariosInactivos = $usuarios->where('activo', 0)->count();

        // Fichajes de hoy para la empresa
        $fichajesHoy = Fichaje::whereHas('usuario', function($q) use ($user) {
            $q->where('id_empresa', $user->id_empresa);
        })->whereDate('timestamp', now()->toDateString());

        $totalEntradasHoy = $fichajesHoy->where('tipo', 'entrada')->count();
        $totalSalidasHoy = $fichajesHoy->where('tipo', 'salida')->count();

        // Resumen diario (suma de tiempo trabajado y pausas de hoy)
        $resumenHoy = ResumenDiario::whereDate('fecha', now()->toDateString())
            ->whereHas('usuario', function($q) use ($user) {
                $q->where('id_empresa', $user->id_empresa);
            })
            ->get();

        $tiempoTrabajadoTotal = $resumenHoy->sum('tiempo_trabajado');
        $tiempoPausasTotal = $resumenHoy->sum('tiempo_pausas');

        // Últimos 5 fichajes de la empresa
        $ultimosFichajes = Fichaje::whereHas('usuario', function($q) use ($user) {
            $q->where('id_empresa', $user->id_empresa);
        })->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalUsuariosActivos',
            'totalUsuariosInactivos',
            'totalEntradasHoy',
            'totalSalidasHoy',
            'tiempoTrabajadoTotal',
            'tiempoPausasTotal',
            'ultimosFichajes'
        ));
    }
}
