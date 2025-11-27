<?php

namespace App\Http\Controllers;

use App\Models\Fichaje;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\ResumenDiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Fichaje\FichajeFactory;

/**
 * @OA\Tag(
 *   name="Fichaje",
 *   description="Operaciones de fichaje (vistas y acciones web)"
 * )
 */

class FichajeController extends Controller
{
    /**
     * EMPLEADO → botón de fichar + su propio resumen
     */

    /**
     * @OA\Get(
     *   path="/fichajes/create",
     *   tags={"Fichaje"},
     *   summary="Formulario de fichaje (vista empleado)",
     *   @OA\Response(response=200, description="HTML view")
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
            'usuario' => $user,
            'ultimo'  => $ultimo,
            'resumen' => $resumen,
            'ultimoTipo'  => $ultimoTipo,
        ]);
    }

    /**
     * @OA\Post(
     *   path="/fichajes",
     *   tags={"Fichaje"},
     *   summary="Registrar fichaje (form submission)",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *         @OA\Property(property="tipo", type="string", example="entrada"),
     *         @OA\Property(property="metodo", type="string", example="web"),
     *         @OA\Property(property="lat", type="number", format="float"),
     *         @OA\Property(property="lng", type="number", format="float"),
     *         @OA\Property(property="notas", type="string")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=302, description="Redirect back with success"),
     *   @OA\Response(response=422, description="Validation error")
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

        $strategy->fichar($user, $datos);

        // Recalcula resumen del día (misma firma que antes)
        \App\Services\Resumen\ResumenService::recalcular(
            $user->id,
            now()->toDateString()
        );

        return back()->with('success', 'Movimiento registrado.');
    }
    /**
     * ENCARGADO / ADMIN → resumen general
     */

    /**
     * @OA\Get(
     *   path="/fichajes",
     *   tags={"Fichaje"},
     *   summary="Resumen general (encargado/admin)",
     *   @OA\Parameter(name="fecha", in="query", @OA\Schema(type="string", format="date")),
     *   @OA\Response(response=200, description="HTML view")
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

        // Resumenes ya calculados
        $resumenes = ResumenDiario::whereDate('fecha', $fecha)
            ->whereIn('user_id', $empleados->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('resumen.res_gnral', [
            'empleados'  => $empleados,
            'resumenes'  => $resumenes,
            'fecha'      => $fecha
        ]);
    }

    /**
     * @OA\Get(
     *   path="/fichajes/resumen-empresa",
     *   tags={"Fichaje"},
     *   summary="Resumen de empresa por fecha",
     *   @OA\Parameter(name="fecha", in="query", @OA\Schema(type="string", format="date")),
     *   @OA\Parameter(name="empresa_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="HTML view")
     * )
     */
    public function resumenEmpresa(Request $request)
    {
        $user = Auth::user();
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
     * @OA\Get(
     *   path="/fichajes/estado-empresa",
     *   tags={"Fichaje"},
     *   summary="Estado actual de empleados por empresa",
     *   @OA\Parameter(name="empresa_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="HTML view")
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
            $emp->hora_estado  = $ultimo?->fecha_hora;
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
