<?php

namespace App\Http\Controllers;

use App\Models\Fichaje;
use App\Models\MetodoFichaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FichajeController extends Controller
{

    public function index()
    {
        $usuario = Auth::user();

        // Si es encargado → ve sus fichajes y los de empleados de su empresa
        if ($usuario->rol->nombre === 'encargado') {
            $fichajes = Fichaje::where('id_usuario', $usuario->id_usuario)
                ->orWhereHas('usuario', function ($q) use ($usuario) {
                    $q->where('id_empresa', $usuario->id_empresa);
                })->get();
        } else {
            // Admin_sistema → puede ver todo
            // Empleado → solo los suyos
            $fichajes = $usuario->rol->nombre === 'admin_sistema'
                ? Fichaje::all()
                : Fichaje::where('id_usuario', $usuario->id_usuario)->get();
        }

        return view('fichajes.index', compact('fichajes'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'id_metodo' => 'required|exists:metodos_fichaje,id_metodo',
            'tipo' => 'required|in:entrada,salida,pausa_inicio,pausa_fin',
        ]);

        Fichaje::create([
            'id_usuario' => Auth::user()->id_usuario,
            'id_metodo' => $request->id_metodo,
            'tipo' => $request->tipo,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'notas' => $request->notas,
        ]);

        return back()->with('success', 'Fichaje registrado correctamente');
    }
}