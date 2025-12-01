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
     * EMPLEADO → Vista de fichar
     */
    public function create()
    {
        $user = Auth::user();

        // Bloqueados no pueden fichar
        if ($user->estado === 'bloqueado') {
            return back()->with('error', 'No puedes fichar porque tu cuenta está bloqueada.');
        }

        $hoy = Carbon::today()->toDateString();

        $resumen = ResumenDiario::where('user_id', $user->id)
            ->whereDate('fecha', $hoy)
            ->first();

        $ultimo = Fichaje::where('user_id', $user->id)
            ->latest('fecha_hora')
            ->first();

        return view('fichajes.fichar', [
            'usuario'    => $user,
            'ultimo'     => $ultimo,
            'resumen'    => $resumen,
            'ultimoTipo' => $ultimo->tipo ?? null,
        ]);
    }

    /**
     * Registrar un fichaje
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // No permitir a usuarios bloqueados
        if ($user->estado === 'bloqueado') {
            return back()->with('error', 'Tu cuenta está bloqueada y no puedes fichar.');
        }

        // Pendientes tampoco deben fichar
        if ($user->estado === 'pendiente') {
            return back()->with('error', 'Debes activar tu cuenta antes de fichar.');
        }

        $request->validate([
            'tipo'   => 'required|in:entrada,salida,pausa,reanudar',
            'metodo' => 'nullable|string|in:web,pwa,nfc,pc',
            'lat'    => 'required|numeric',
            'lng'    => 'required|numeric',
            'notas'  => 'nullable|string|max:500',
        ]);

        $metodo = $request->input('metodo', 'web');

        // Control de método activo por jornada
        $cacheKey = 'fichaje_metodo_activo_' . $user->id;
        $metodoActivo = cache()->get($cacheKey);

        if ($metodoActivo && $metodoActivo !== $metodo) {
            return back()->with('error', 'Debes finalizar el fichaje con el mismo método.');
        }

        // Validar estrategia
        try {
            $strategy = FichajeFactory::make($metodo);
        } catch (\Throwable $e) {
            return back()->with('error', 'Método de fichaje inválido.');
        }

        // GPS obligado
        if (!$request->lat || !$request->lng) {
            return back()->with('error', 'No se puede fichar sin ubicación GPS.');
        }

        $datos = $request->only(['tipo', 'lat', 'lng', 'notas']);

        // Registrar fichaje mediante la estrategia
        $strategy->fichar($user, $datos);

        // Guardar método activo de jornada
        if (in_array($datos['tipo'], ['entrada', 'reanudar'])) {
            cache()->put($cacheKey, $metodo, now()->addHours(24));
        }

        if ($datos['tipo'] === 'salida') {
            cache()->forget($cacheKey);
        }

        // Auditoría
        AuditoriaService::log(
            'fichaje_' . $datos['tipo'],
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
            'Fichaje realizado por el usuario'
        );

        // Recalcula resumen del día
        \App\Services\Resumen\ResumenService::recalcular(
            $user->id,
            now()->toDateString()
        );

        return back()->with('success', 'Movimiento registrado.');
    }

    /**
     * ADMIN / ENCARGADO → Resumen general
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $fecha = $request->input('fecha', Carbon::today()->toDateString());

        $empleados = $user->rol->nombre === 'admin_sistema'
            ? Usuario::with(['empresa', 'rol'])->get()
            : Usuario::where('empresa_id', $user->empresa_id)->with(['empresa', 'rol'])->get();

        $resumenes = ResumenDiario::whereDate('fecha', $fecha)
            ->whereIn('user_id', $empleados->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('resumen.res_gnral', compact('empleados', 'resumenes', 'fecha'));
    }

    /**
     * Resumen por empresa
     */
    public function resumenEmpresa(Request $request)
    {
        $user = Auth::user();
        $fecha = $request->input('fecha', now()->toDateString());

        $empresaId = $user->rol->nombre === 'admin_sistema'
            ? $request->empresa_id
            : $user->empresa_id;

        $empresa = Empresa::findOrFail($empresaId);

        $empleados = Usuario::where('empresa_id', $empresaId)->get();

        $resumenes = ResumenDiario::whereDate('fecha', $fecha)
            ->whereIn('user_id', $empleados->pluck('id'))
            ->with('usuario')
            ->get();

        $totalTrabajado = $resumenes->sum('tiempo_trabajado');
        $totalPausas    = $resumenes->sum('tiempo_pausas');

        $jornada = $empresa->jornada_diaria_minutos;

        $alertas = [];
        foreach ($resumenes as $r) {
            if ($r->tiempo_trabajado > $jornada) {
                $alertas[] = "{$r->usuario->name} ha superado la jornada";
            }
        }

        $empresas = $user->rol->nombre === 'admin_sistema' ? Empresa::all() : [];

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

    /**
     * Estado de empleados de empresa
     */
    public function estadoEmpresa(Request $request)
    {
        $user = Auth::user();

        $empresaId = $user->rol->nombre === 'admin_sistema'
            ? $request->empresa_id
            : $user->empresa_id;

        $query = Usuario::where('empresa_id', $empresaId);

        if ($request->filled('empleado')) {
            $query->where('name', 'like', '%' . $request->empleado . '%');
        }

        $empleados = $query->get();

        $desde = $request->filled('desde') ? Carbon::parse($request->desde)->toDateString() : null;
        $hasta = $request->filled('hasta') ? Carbon::parse($request->hasta)->toDateString() : null;

        $anio = $request->input('anio', now()->year);

        foreach ($empleados as $key => $emp) {

            // Último fichaje
            $ultimo = $emp->fichajes()->latest('fecha_hora')->first();
            $emp->estado_actual = $ultimo->tipo ?? 'salida';
            $emp->hora_estado   = $ultimo?->fecha_hora;

            // Filtro por estado
            if ($request->filled('estado') && $emp->estado_actual !== $request->estado) {
                unset($empleados[$key]);
                continue;
            }

            // Horas del día
            if ($request->filled('fecha')) {
                $fecha = Carbon::parse($request->fecha)->toDateString();

                $r = ResumenDiario::where('user_id', $emp->id)
                    ->whereDate('fecha', $fecha)
                    ->first();

                $emp->horas_dia = $r->tiempo_trabajado ?? 0;
            }

            // Horas del año
            $emp->minutos_anio = ResumenDiario::where('user_id', $emp->id)
                ->whereYear('fecha', $anio)
                ->sum('tiempo_trabajado');

            // Filtro rango fechas
            if ($desde && $hasta) {
                $tiene = ResumenDiario::where('user_id', $emp->id)
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->exists();

                if (!$tiene) {
                    unset($empleados[$key]);
                }
            }
        }

        $empresas = $user->rol->nombre === 'admin_sistema' ? Empresa::all() : [];

        return view('empresa.estado', compact('empleados', 'empresaId', 'empresas'));
    }
}
