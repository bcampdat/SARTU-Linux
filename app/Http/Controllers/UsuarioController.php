<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


/**
 * @OA\Tag(
 *   name="Usuario",
 *   description="Operaciones sobre usuarios (vistas web)"
 * )
 */

class UsuarioController extends Controller
{
    /**
     * @OA\Get(
     *   path="/usuarios",
     *   tags={"Usuario"},
     *   summary="Listado de usuarios (vista)",
     *   @OA\Response(response=200, description="HTML view")
     * )
     */
    public function index()
    {
        $usuario = Auth::user();

        // ADMIN → todos
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

        $usuarios = $usuarios->filter(function ($u) {
            return $u->rol !== null;
        });

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * @OA\Get(
     *   path="/usuarios/create",
     *   tags={"Usuario"},
     *   summary="Formulario creación usuario",
     *   @OA\Response(response=200, description="HTML view")
     * )
     */
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

      /**
     * @OA\Post(
     *   path="/usuarios",
     *   tags={"Usuario"},
     *   summary="Crear usuario (form submission)",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="email", type="string", format="email"),
     *         @OA\Property(property="rol_id", type="integer"),
     *         @OA\Property(property="empresa_id", type="integer")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=302, description="Redirect (HTML)"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */

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

    /**
     * @OA\Get(
     *   path="/usuarios/{usuario}/edit",
     *   tags={"Usuario"},
     *   summary="Editar usuario (vista)",
     *   @OA\Parameter(name="usuario", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="HTML view")
     * )
     */
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

    /**
     * @OA\Put(
     *   path="/usuarios/{usuario}",
     *   tags={"Usuario"},
     *   summary="Actualizar usuario (form submission)",
     *   @OA\Parameter(name="usuario", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="email", type="string", format="email"),
     *         @OA\Property(property="rol_id", type="integer"),
     *         @OA\Property(property="empresa_id", type="integer"),
     *         @OA\Property(property="activo", type="boolean")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=302, description="Redirect (HTML)"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
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

    /**
     * @OA\Delete(
     *   path="/usuarios/{usuario}",
     *   tags={"Usuario"},
     *   summary="Eliminar usuario",
     *   @OA\Parameter(name="usuario", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=302, description="Redirect (HTML)")
     * )
     */
    public function destroy(Usuario $usuario)
    {
        $user = Auth::user();

        if ($user->rol->nombre === 'encargado' && $usuario->empresa_id != $user->empresa_id) {
            abort(403);
        }

        $usuario->delete();

        return back()->with('success', 'Usuario eliminado.');
    }

    public function empleadosEmpresa()
    {
        $user = Auth::user();

        // Encargado ve solo los empleados de su empresa
        $empleados = Usuario::where('empresa_id', $user->empresa_id)
            ->with('rol')
            ->get();

        return view('usuarios.encargado_index', compact('empleados'));
    }
}
