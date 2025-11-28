<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\PDF;


class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Auditoria::with('usuario');

        // Filtros
        if ($request->filled('from')) {
            $query->whereDate('fecha_creacion', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('fecha_creacion', '<=', $request->to);
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('usuario_id')) {
            $query->where('user_id', $request->usuario_id);
        }

        // Visibilidad por rol (SEGURIDAD)
        if ($user->rol->nombre === 'admin_sistema') {
            
        } elseif ($user->rol->nombre === 'encargado') {
            $query->whereHas('usuario', function ($q) use ($user) {
                $q->where('empresa_id', $user->empresa_id);
            });
        } else {
            abort(403);
        }

        $logs = $query->orderBy('fecha_creacion', 'desc')->paginate(50);

        // USUARIOS PARA EL FILTRO (SEGUROS)
        if ($user->rol->nombre === 'admin_sistema') {
            $usuariosFiltro = \App\Models\Usuario::orderBy('email')
                ->get(['id', 'email']);
        } else {
            $usuariosFiltro = \App\Models\Usuario::where('empresa_id', $user->empresa_id)
                ->orderBy('email')
                ->get(['id', 'email']);
        }

        return view('auditoria.index', compact('logs', 'usuariosFiltro'));
    }


    public function exportPdf(Request $request)
    {
        $user = Auth::user();

        $query = Auditoria::with('usuario');

        if ($request->filled('from')) {
            $query->whereDate('fecha_creacion', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('fecha_creacion', '<=', $request->to);
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('usuario_id')) {
            $query->where('user_id', $request->usuario_id);
        }

        if ($user->rol->nombre === 'admin_sistema') {

        } elseif ($user->rol->nombre === 'encargado') {
            $query->whereHas('usuario', function ($q) use ($user) {
                $q->where('empresa_id', $user->empresa_id);
            });
        } else {
            abort(403);
        }

        $logs = $query->orderBy('fecha_creacion', 'asc')->get();

        $data = [
            'logs'         => $logs,
            'generadoPor'  => $user,
            'filtros'      => [
                'from'       => $request->from,
                'to'         => $request->to,
                'usuario_id' => $request->usuario_id,
                'accion'     => $request->accion,
            ],
            'fechaEmision' => now(),
        ];

        $pdf = app()->make(\Barryvdh\DomPDF\PDF::class);
        $pdf->loadView('auditoria.pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        $fileName = 'auditoria_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}

