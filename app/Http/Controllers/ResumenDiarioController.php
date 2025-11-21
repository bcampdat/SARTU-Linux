<?php

namespace App\Http\Controllers;

use App\Models\ResumenDiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumenDiarioController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Usuario no autenticado');
        }

        $esEncargado = $user->rol?->nombre === 'encargado';

        if ($esEncargado) {
            $resumen = ResumenDiario::where('id_usuario', $user->id_usuario)
                ->orWhereHas('usuario', function ($q) use ($user) {
                    $q->where('id_empresa', $user->id_empresa);
                })
                ->get();
        } else {
            $resumen = ResumenDiario::where('id_usuario', $user->id_usuario)->get();
        }

        return view('resumen.index', compact('resumen'));
    }
}
