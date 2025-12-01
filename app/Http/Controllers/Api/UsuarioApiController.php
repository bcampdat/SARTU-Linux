<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditoriaService;

class UsuarioApiController extends Controller
{
    // LISTAR USUARIOS (ADMIN / ENCARGADO)
    public function index(Request $request)
    {
        $user = $request->user();

        if (!in_array($user->rol->nombre, ['admin_sistema', 'encargado'])) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $query = $user->rol->nombre === 'admin_sistema'
            ? Usuario::with(['empresa', 'rol'])
            : Usuario::where('empresa_id', $user->empresa_id)->with(['empresa', 'rol']);

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        return response()->json(
            $query->orderBy('email')->paginate(20)
        );
    }

    // CREAR USUARIO
    public function store(Request $request)
    {
        $user = $request->user();

        if (!in_array($user->rol->nombre, ['admin_sistema', 'encargado'])) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'empresa_id' => 'required|exists:empresas,id_empresa',
            'rol_id'     => 'required|exists:roles,id_rol',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
        ]);

        // 🔒 Encargado solo puede crear en su empresa y rol empleado
        if ($user->rol->nombre === 'encargado') {
            if ($request->empresa_id != $user->empresa_id) {
                return response()->json(['error' => 'Empresa no permitida'], 403);
            }
        }

        // Contraseña temporal automática como en web
        $tempPassword = substr(
            str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'),
            0,
            10
        );

        $usuario = Usuario::create([
            'empresa_id' => $request->empresa_id,
            'rol_id'     => $request->rol_id,
            'name'       => strip_tags($request->name),
            'email'      => $request->email,
            'password'   => Hash::make($tempPassword),
            'estado'     => 'pendiente',
            'activo'     => 0,
        ]);

        AuditoriaService::log(
            'crear_usuario_api',
            'Usuario',
            $usuario->id,
            null,
            $usuario->toArray(),
            'Usuario creado por API'
        );

        return response()->json([
            'usuario' => $usuario,
            'password_temporal' => $tempPassword
        ], 201);
    }

    public function show(Request $request, Usuario $usuario)
    {
        $user = $request->user();

        if (
            $user->rol->nombre === 'encargado' &&
            $usuario->empresa_id != $user->empresa_id
        ) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json(
            $usuario->load(['empresa', 'rol'])
        );
    }

    public function update(Request $request, Usuario $usuario)
    {
        $user = $request->user();

        if (
            $user->rol->nombre === 'encargado' &&
            $usuario->empresa_id != $user->empresa_id
        ) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if ($usuario->estado === 'bloqueado') {
            return response()->json([
                'error' => 'El usuario está bloqueado'
            ], 409);
        }

        $request->validate([
            'empresa_id' => 'required|exists:empresas,id_empresa',
            'rol_id'     => 'required|exists:roles,id_rol',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $usuario->id,
            'estado'     => 'required|in:activo,bloqueado,pendiente',
        ]);

        $antes = $usuario->toArray();

        $usuario->update([
            'empresa_id' => $request->empresa_id,
            'rol_id'     => $request->rol_id,
            'name'       => strip_tags($request->name),
            'email'      => $request->email,
            'estado'     => $request->estado,
            'activo'     => $request->estado === 'activo' ? 1 : 0,
        ]);

        AuditoriaService::log(
            'editar_usuario_api',
            'Usuario',
            $usuario->id,
            $antes,
            $usuario->toArray(),
            'Usuario actualizado por API'
        );

        return response()->json($usuario);
    }

    public function destroy(Request $request, Usuario $usuario)
    {
        $user = $request->user();

        if (
            $user->rol->nombre === 'encargado' &&
            $usuario->empresa_id != $user->empresa_id
        ) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        AuditoriaService::log(
            'eliminar_usuario_api',
            'Usuario',
            $usuario->id,
            $usuario->toArray(),
            null,
            'Usuario eliminado por API'
        );

        $usuario->delete();

        return response()->json(['ok' => true]);
    }
}
