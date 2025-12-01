<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\PDF;

//composer require barryvdh/laravel-dompdf


class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Auditoria::with(['usuario.rol', 'usuario.empresa']);

        // === FILTROS BASE ===
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

        // === FILTRO POR EMPRESA + SEGURIDAD ===
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
            abort(403);
        }

        $logs = $query->orderBy('fecha_creacion', 'desc')->paginate(50);

        // === USUARIOS PARA FILTRO DEPENDIENTES DE EMPRESA ===
        if ($user->rol->nombre === 'admin_sistema') {

            $empresas = \App\Models\Empresa::orderBy('nombre')->get();

            if ($request->filled('empresa_id')) {
                $usuariosFiltro = \App\Models\Usuario::where('empresa_id', $request->empresa_id)
                    ->orderBy('email')
                    ->get(['id', 'email']);
            } else {
                $usuariosFiltro = \App\Models\Usuario::orderBy('email')
                    ->get(['id', 'email']);
            }
        } else {

            $usuariosFiltro = \App\Models\Usuario::where('empresa_id', $user->empresa_id)
                ->orderBy('email')
                ->get(['id', 'email']);

            $empresas = collect();
        }

        return view('auditoria.index', compact(
            'logs',
            'usuariosFiltro',
            'empresas'
        ));
    }
    public function exportPdf(Request $request)
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
            abort(403);
        }

        $logs = $query->orderBy('fecha_creacion', 'asc')->get();

        $data = [
            'logs'         => $logs,
            'generadoPor'  => $user,
            'filtros'      => $request->only([
                'from',
                'to',
                'usuario_id',
                'accion',
                'empresa_id'
            ]),
            'fechaEmision' => now(),
        ];

        $pdf = app()->make(\Barryvdh\DomPDF\PDF::class);
        $pdf->loadView('auditoria.pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download(
            'auditoria_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}
