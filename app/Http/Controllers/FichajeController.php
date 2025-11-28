<?php

namespace App\Http\Controllers;

use App\Services\Fichaje\FichajeFactory;
use App\Models\Fichaje;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\ResumenDiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\AuditoriaService;

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

        $ultimoTipo = $ultimo->tipo ?? null;

        return view('fichajes.fichar', [
            'usuario'    => $user,
            'ultimo'     => $ultimo,
            'resumen'    => $resumen,
            'ultimoTipo' => $ultimoTipo,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'tipo'   => 'required|in:entrada,salida,pausa,reanudar',
            'metodo' => 'nullable|string|in:web,pwa,nfc,pc',
            'lat'    => 'nullable|numeric',
            'lng'    => 'nullable|numeric',
            'notas'  => 'nullable|string',
        ]);

        $metodo = $request->input('metodo', 'web');

        try {
            $strategy = FichajeFactory::make($metodo);
        } catch (\Throwable $e) {
            // fallback a web si hay error en la fábrica
            $strategy = FichajeFactory::make('web');
        }

        $datos = $request->only(['tipo', 'lat', 'lng', 'notas']);

        // ejecutar el fichaje
        $strategy->fichar($user, $datos);

        // registrar en auditoría
        AuditoriaService::log(
            'fichaje_' . $datos['tipo'],
            'Fichaje',
            null,
            null,
            [
                'user_id' => $user->id,
                'tipo'    => $datos['tipo'],
                'metodo'  => $metodo,
                'lat'     => $datos['lat'] ?? null,
                'lng'     => $datos['lng'] ?? null,
                'notas'   => $datos['notas'] ?? null,
            ],
            'Fichaje realizado por el usuario'
        );

        // recalcular resumen diario
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

        $resumenes = ResumenDiario::whereDate('fecha', $fecha)
            ->whereIn('user_id', $empleados->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('resumen.res_gnral', [
            'empleados' => $empleados,
            'resumenes' => $resumenes,
            'fecha'     => $fecha
        ]);
    }

    public function resumenEmpresa(Request $request)
    {
        $user  = Auth::user();
        $fecha = $request->get('fecha', now()->toDateString());

        if ($user->rol->nombre === 'admin_sistema') {
            $empresaId = $request->empresa_id;
        } else {
            $empresaId = $user->empresa_id;
        }

        $empleados = Usuario::where('empresa_id', $empresaId)->get();

        $resumenes = ResumenDiario::whereDate('fecha', $fecha)
            ->whereIn('user_id', $empleados->pluck('id'))
            ->with('usuario')
            ->get();

        $totalTrabajado = $resumenes->sum('tiempo_trabajado');
        $totalPausas    = $resumenes->sum('tiempo_pausas');

        $jornada = Empresa::find($empresaId)->jornada_diaria_minutos;

        $alertas = [];

        foreach ($resumenes as $r) {
            if ($r->tiempo_trabajado > $jornada) {
                $alertas[] = $r->usuario->name . ' ha superado la jornada';
            }
        }

        $empresas = [];
        if ($user->rol->nombre === 'admin_sistema') {
            $empresas = Empresa::all();
        }

        return view('empresa.resumen', compact(
            'resumenes',
            'totalTrabajado',
            'totalPausas',
            'alertas',
            'empresaId',
            'empresas',
            'fecha'
        ));
    }

    public function estadoEmpresa(Request $request)
    {
        $user = Auth::user();

        if ($user->rol->nombre === 'admin_sistema') {
            $empresaId = $request->empresa_id;
        } else {
            $empresaId = $user->empresa_id;
        }

        $empleados = Usuario::where('empresa_id', $empresaId)->get();

        foreach ($empleados as $emp) {
            $ultimo = $emp->fichajes()->latest('fecha_hora')->first();
            $emp->estado_actual = $ultimo->tipo ?? 'salida';
            $emp->hora_estado   = $ultimo?->fecha_hora;
        }

        $empresas = [];
        if ($user->rol->nombre === 'admin_sistema') {
            $empresas = Empresa::all();
        }

        return view('empresa.estado', compact(
            'empleados',
            'empresaId',
            'empresas'
        ));
    }
}
