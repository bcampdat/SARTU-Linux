<?php

namespace App\Http\Controllers;

use App\Models\ResumenDiario;
use Illuminate\Http\Request;

class ResumenDiarioController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            abort(401, 'Usuario no autenticado');
        }

        $esEncargado = $user->rol?->nombre === 'encargado';

        if ($esEncargado) {
            $resumen = ResumenDiario::where('id_usuario', $user->id)
                ->orWhereHas('usuario', function ($q) use ($user) {
                    $q->where('id_empresa', $user->id_empresa);
                })
                ->get();
        } else {
            $resumen = ResumenDiario::where('id_usuario', $user->id)->get();
        }

        return view('resumen.index', compact('resumen'));
    }
}
