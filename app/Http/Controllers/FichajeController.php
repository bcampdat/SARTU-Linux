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

/**
 * @OA\Tag(
 *     name="Fichajes",
 *     description="Operaciones de fichaje y resúmenes"
 * )
 */

class FichajeController extends Controller
{
    /**
     * EMPLEADO → botón de fichar + su propio resumen
     *
     * @OA\Get(
     *     path="/fichajes/create",
     *     operationId="fichajeCreate",
     *     tags={"Fichajes"},
     *     summary="Vista para fichar",
     *     description="Muestra el botón/forma para que el empleado realice un fichaje y su resumen diario",
     *     @OA\Response(response=200, description="Vista mostrada correctamente"),
     *     security={{"sanctum":{}}}
     * )
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

    /**
     * Registrar fichaje del usuario
     *
     * @OA\Post(
     *     path="/fichajes",
     *     operationId="fichajeStore",
     *     tags={"Fichajes"},
     *     summary="Registrar fichaje",
     *     description="Registra un fichaje (entrada, salida, pausa, reanudar). Registra auditoría y recalcula resúmenes.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tipo"},
     *             @OA\Property(property="tipo", type="string", example="entrada", description="entrada|salida|pausa|reanudar"),
     *             @OA\Property(property="metodo", type="string", example="web", description="web|pwa|nfc|pc"),
     *             @OA\Property(property="lat", type="number", format="float", example=40.4168),
     *             @OA\Property(property="lng", type="number", format="float", example=-3.7038),
     *             @OA\Property(property="notas", type="string", example="Entrada desde oficina")
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirección tras fichaje"),
     *     @OA\Response(response=422, description="Validación fallida"),
     *     security={{"sanctum":{}}}
     * )
     */

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
     *
     * @OA\Get(
     *     path="/fichajes",
     *     operationId="fichajeIndex",
     *     tags={"Fichajes"},
     *     summary="Resumen general de fichajes",
     *     description="Lista resúmenes diarios de empleados para una fecha dada. Admin ve todas las empresas, encargado la suya.",
     *     @OA\Parameter(
     *         name="fecha",
     *         in="query",
     *         description="Fecha a consultar (YYYY-MM-DD). Si no se indica, hoy.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(response=200, description="Resumen mostrado"),
     *     security={{"sanctum":{}}}
     * )
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

    /**
     * Resumen por empresa
     *
     * @OA\Get(
     *     path="/fichajes/resumen-empresa",
     *     operationId="resumenEmpresa",
     *     tags={"Fichajes"},
     *     summary="Resumen por empresa",
     *     description="Obtiene el resumen diario de una empresa (admin puede filtrar por empresa_id)",
     *     @OA\Parameter(
     *         name="fecha",
     *         in="query",
     *         description="Fecha a consultar (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="empresa_id",
     *         in="query",
     *         description="ID de la empresa (solo para admin)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Resumen por empresa mostrado"),
     *     security={{"sanctum":{}}}
     * )
     */

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

    /**
     * Estado actual por empresa
     *
     * @OA\Get(
     *     path="/fichajes/estado-empresa",
     *     operationId="estadoEmpresa",
     *     tags={"Fichajes"},
     *     summary="Estado actual de empleados por empresa",
     *     description="Devuelve estado actual (entrada/salida) y hora del último movimiento para empleados de una empresa",
     *     @OA\Parameter(
     *         name="empresa_id",
     *         in="query",
     *         description="ID de la empresa (solo para admin)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Estado mostrado"),
     *     security={{"sanctum":{}}}
     * )
     */
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
