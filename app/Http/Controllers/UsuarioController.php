<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = auth()->user()->rol->nombre === 'admin'
            ? Usuario::all()
            : Usuario::where('id_empresa', auth()->user()->id_empresa)->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create', [
            'empresa' => auth()->user()->empresa,
            'roles' => Rol::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:6',
            'id_rol' => 'required|exists:roles,id_rol'
        ]);

        Usuario::create($request->all());

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado');
    }
}
