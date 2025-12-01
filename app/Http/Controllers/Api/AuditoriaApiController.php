<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditoriaApiController extends Controller
{
    /**
     * LISTADO DE AUDITORÍA (API REST)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Auditoria::with(['usuario.rol', 'usuario.empresa']);

        // === FILTROS ===
        if ($request->filled('from')) {
            $query->whereDate('fecha_creacion', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('fecha_creacion', '<=', $request->to);
        }

        if ($request->filled('accion')) {
            $query->where('accion', 'like', '%' . $request->accion . '%');
        }

        if ($request->filled('usuario_id')) {
            $query->where('user_id', $request->usuario_id);
        }

        // === SEGURIDAD POR ROL ===
        if ($user->rol->nombre === 'admin_sistema') {

            if ($request->filled('empresa_id')) {
                $query->whereHas('usuario', function ($q) use ($request) {
                    $q->where('empresa_id', $request->empresa_id);
                });
            }

        } elseif ($user->rol->nombre === 'encargado') {

            $query->whereHas('usuario', function ($q) use ($user) {
                $q->where('empresa_id', $user->empresa_id);
            });

        } else {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $logs = $query->orderBy('fecha_creacion', 'desc')->paginate(50);

        return response()->json($logs);
    }

    /**
     * EXPORTAR AUDITORÍA (SOLO DATOS, NO PDF)
     */
    public function exportData(Request $request)
    {
        $user = Auth::user();

        $query = Auditoria::with(['usuario.rol', 'usuario.empresa']);

        if ($request->filled('from')) {
            $query->whereDate('fecha_creacion', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('fecha_creacion', '<=', $request->to);
        }

        if ($request->filled('accion')) {
            $query->where('accion', 'like', '%' . $request->accion . '%');
        }

        if ($request->filled('usuario_id')) {
            $query->where('user_id', $request->usuario_id);
        }

        if ($user->rol->nombre === 'admin_sistema') {

            if ($request->filled('empresa_id')) {
                $query->whereHas('usuario', function ($q) use ($request) {
                    $q->where('empresa_id', $request->empresa_id);
                });
            }

        } elseif ($user->rol->nombre === 'encargado') {

            $query->whereHas('usuario', function ($q) use ($user) {
                $q->where('empresa_id', $user->empresa_id);
            });

        } else {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $logs = $query->orderBy('fecha_creacion', 'asc')->get();

        return response()->json([
            'generado_por' => $user,
            'fecha'        => now(),
            'filtros'      => $request->all(),
            'logs'         => $logs,
        ]);
    }
}
