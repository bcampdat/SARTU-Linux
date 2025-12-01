<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditoriaService;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // ADMIN → todos
        if ($usuario->rol->nombre === 'admin_sistema') {
            $usuarios = Usuario::with(['empresa', 'rol'])->get();
        }
        // ENCARGADO → solo su empresa
        else if ($usuario->rol->nombre === 'encargado') {
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

        $usuarios = $usuarios->filter(function ($u) {
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
        } elseif ($usuario->rol->nombre === 'encargado') {
            $empresas = Empresa::where('id_empresa', $usuario->empresa_id)->get();
            $roles = Rol::where('nombre', 'empleado')->get();
        } else {
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

            if (
                $rolSolicitado->nombre !== 'empleado' ||
                $request->empresa_id != $usuario->empresa_id
            ) {
                abort(403, 'No puedes crear ese tipo de usuario.');
            }
        }

        $tempPassword = substr(
            str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'),
            0,
            10
        );

        $usuarioCreado = Usuario::create([
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

        //  AUDITORÍA: creación de usuario
        AuditoriaService::log(
            'crear_usuario',
            'Usuario',
            $usuarioCreado->id,
            null,
            [
                'name'       => $usuarioCreado->name,
                'email'      => $usuarioCreado->email,
                'rol_id'     => $usuarioCreado->rol_id,
                'empresa_id' => $usuarioCreado->empresa_id,
                'estado'     => $usuarioCreado->estado,
                'activo'     => $usuarioCreado->activo,
            ],
            'Usuario creado con contraseña temporal'
        );

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

        //  BLOQUEO TOTAL
        if ($usuario->estado === 'bloqueado') {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'Este usuario está bloqueado y no puede modificarse.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $usuario->id,
            'rol_id'      => 'required|exists:roles,id_rol',
            'empresa_id'  => 'required|exists:empresas,id_empresa',
        ]);

        $antes = $usuario->toArray();

        $usuario->update([
            'name'        => $request->name,
            'email'       => $request->email,
            'rol_id'      => $request->rol_id,
            'empresa_id'  => $request->empresa_id,
        ]);

        AuditoriaService::log(
            'editar_usuario',
            'Usuario',
            $usuario->id,
            $antes,
            $usuario->toArray(),
            'Usuario actualizado'
        );

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function bloquear(Usuario $usuario)
    {
        $user = Auth::user();

        // Solo el admin_sistema puede bloquear
        if ($user->rol->nombre !== 'admin_sistema') {
            abort(403);
        }

        // Guardamos estado anterior para auditoría
        $antes = $usuario->toArray();

        // Cambiamos a bloqueado
        $usuario->estado = 'bloqueado';
        $usuario->activo = 0; // por coherencia: bloqueado = inactivo
        $usuario->save();

        // AUDITORÍA DEL BLOQUEO
        AuditoriaService::log(
            'bloquear_usuario',
            'Usuario',
            $usuario->id,
            $antes,
            $usuario->toArray(),
            'Usuario bloqueado por admin_sistema'
        );

        return back()->with('success', 'Usuario bloqueado correctamente.');
    }

    public function desbloquear(Usuario $usuario)
    {
        $user = Auth::user();

        // Solo admin_sistema puede desbloquear
        if ($user->rol->nombre !== 'admin_sistema') {
            abort(403);
        }

        // Solo se puede desbloquear si está bloqueado
        if ($usuario->estado !== 'bloqueado') {
            return back()->with('error', 'El usuario no está bloqueado.');
        }

        $antes = $usuario->toArray();

        // Generamos Nueva contraseña temporal
        $tempPassword = substr(
            str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'),
            0,
            10
        );

        // Reset completo del usuario (como nuevo)
        $usuario->estado = 'pendiente';
        $usuario->activo = 0;
        $usuario->password = Hash::make($tempPassword);
        $usuario->save();

        // Auditoría
        AuditoriaService::log(
            'desbloquear_usuario',
            'Usuario',
            $usuario->id,
            $antes,
            $usuario->toArray(),
            'Usuario desbloqueado y reiniciado con contraseña temporal'
        );

        return back()->with(
            'success',
            "Usuario desbloqueado correctamente. Nueva contraseña temporal: $tempPassword"
        );
    }

    public function destroy(Usuario $usuario)
    {
        $user = Auth::user();

        if ($user->rol->nombre === 'encargado' && $usuario->empresa_id != $user->empresa_id) {
            abort(403);
        }

        // AUDITORÍA: antes de eliminar
        AuditoriaService::log(
            'eliminar_usuario',
            'Usuario',
            $usuario->id,
            $usuario->toArray(),
            null,
            'Usuario eliminado'
        );

        $usuario->delete();

        return back()->with('success', 'Usuario eliminado.');
    }

    public function empleadosEmpresa(Request $request)
    {
        $user = Auth::user();

        $query = Usuario::where('empresa_id', $user->empresa_id)
            ->with('rol');

        //  FILTRO NOMBRE
        if ($request->filled('nombre')) {
            $query->where('name', 'like', '%' . $request->nombre . '%');
        }

        //  FILTRO EMAIL
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // FILTRO ROL
        if ($request->filled('rol')) {
            $query->whereHas('rol', function ($q) use ($request) {
                $q->where('nombre', $request->rol);
            });
        }

        // FILTRO ESTADO REAL
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $empleados = $query->paginate(10)->withQueryString();

        return view('usuarios.encargado_index', compact('empleados'));
    }
}