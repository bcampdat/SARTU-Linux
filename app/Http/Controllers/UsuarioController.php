<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // Admin_sistema → puede ver todos los usuarios
        if ($usuario->rol->nombre === 'admin_sistema') {
            $usuarios = Usuario::with(['empresa', 'rol'])->get();
        }
        // Encargado → ve usuarios de su empresa
        elseif ($usuario->rol->nombre === 'encargado') {
            $usuarios = Usuario::with(['empresa', 'rol'])
                ->where('id_empresa', $usuario->id_empresa)
                ->get();
        }
        // Empleado → solo se ve a sí mismo
        else {
            $usuarios = Usuario::with(['empresa', 'rol'])
                ->where('id', $usuario->id)
                ->get();
        }

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $usuario = Auth::user();
        
        if ($usuario->rol->nombre === 'admin_sistema') {
            $empresas = Empresa::all();
            $roles = Rol::all();
        } elseif ($usuario->rol->nombre === 'encargado') {
            $empresas = Empresa::where('id_empresa', $usuario->id_empresa)->get();
            $roles = Rol::where('nombre', 'empleado')->get();
        } else {
            abort(403, 'No tienes permisos para crear usuarios');
        }

        return view('usuarios.create', compact('empresas', 'roles'));
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'id_rol' => 'required|exists:roles,id_rol',
            'id_empresa' => 'required|exists:empresas,id_empresa'
        ]);

        // Validar permisos según rol
        if ($usuario->rol->nombre === 'encargado') {
            $rolSolicitado = Rol::find($request->id_rol);
            if ($rolSolicitado->nombre !== 'empleado' || $request->id_empresa != $usuario->id_empresa) {
                abort(403, 'No tienes permisos para realizar esta acción');
            }
        }

        // Preparar datos
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_rol' => $request->id_rol,
            'id_empresa' => $usuario->rol->nombre === 'encargado' ? $usuario->id_empresa : $request->id_empresa,
            'activo' => 1
        ];

        Usuario::create($userData);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado correctamente.');
    }
}
