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

        // ADMIN
        if ($usuario->rol->nombre === 'admin_sistema') {
            $usuarios = Usuario::with(['empresa', 'rol'])->get();
        }
        // ENCARGADO → solo su empresa
        elseif ($usuario->rol->nombre === 'encargado') {
            $usuarios = Usuario::with(['empresa', 'rol'])
                ->where('empresa_id', $usuario->empresa_id)
                ->get();
        }
        // EMPLEADO → solo él mismo
        else {
            $usuarios = Usuario::with(['empresa', 'rol'])
                ->where('id', $usuario->id)
                ->get();
        }
        
        $usuarios = $usuarios->filter(function($u) {
            return $u->rol !== null;
        });

        return view('usuarios.index', compact('usuarios'));
    }
    public function create()
    {
        $usuario = Auth::user();

        if ($usuario->rol->nombre === 'admin_sistema') {
            $empresas = Empresa::all();
            $roles = Rol::all();
        }
        elseif ($usuario->rol->nombre === 'encargado') {
            $empresas = Empresa::where('id_empresa', $usuario->empresa_id)->get();
            $roles = Rol::where('nombre', 'empleado')->get();
        }
        else {
            abort(403);
        }

        return view('usuarios.create', compact('empresas', 'roles'));
    }
    public function store(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'rol_id'      => 'required|exists:roles,id_rol',
            'empresa_id'  => 'required|exists:empresas,id_empresa'
        ]);

        if ($usuario->rol->nombre === 'encargado') {
            $rolSolicitado = Rol::find($request->rol_id);

            if ($rolSolicitado->nombre !== 'empleado' ||
                $request->empresa_id != $usuario->empresa_id
            ) {
                abort(403, 'No puedes crear ese tipo de usuario.');
            }
        }

        $tempPassword = substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 10);

        Usuario::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($tempPassword),
            'rol_id'      => $request->rol_id,
            'empresa_id'  => $usuario->rol->nombre === 'encargado'
                ? $usuario->empresa_id
                : $request->empresa_id,
            'estado'      => 'pendiente',
            'activo'      => 0,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', "Usuario creado. Contraseña temporal: $tempPassword");
    }
    public function edit(Usuario $usuario)
    {
        $user = Auth::user();

        if ($user->rol->nombre === 'encargado' && $usuario->empresa_id != $user->empresa_id) {
            abort(403);
        }

        $empresas = Empresa::all();
        $roles = Rol::all();

        return view('usuarios.edit', compact('usuario', 'empresas', 'roles'));
    }
    public function update(Request $request, Usuario $usuario)
    {
        $user = Auth::user();

        if ($user->rol->nombre === 'encargado' && $usuario->empresa_id != $user->empresa_id) {
            abort(403);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $usuario->id,
            'rol_id'      => 'required|exists:roles,id_rol',
            'empresa_id'  => 'required|exists:empresas,id_empresa',
            'activo'      => 'required|boolean'
        ]);

        $usuario->update([
            'name'        => $request->name,
            'email'       => $request->email,
            'rol_id'      => $request->rol_id,
            'empresa_id'  => $request->empresa_id,
            'activo'      => $request->activo,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
    public function destroy(Usuario $usuario)
    {
        $user = Auth::user();

        if ($user->rol->nombre === 'encargado' && $usuario->empresa_id != $user->empresa_id) {
            abort(403);
        }

        $usuario->delete();

        return back()->with('success', 'Usuario eliminado.');
    }
}
