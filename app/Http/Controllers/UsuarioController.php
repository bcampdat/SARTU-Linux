<?php 

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditoriaService;

/**
 * @OA\Tag(
 *     name="Usuarios",
 *     description="Gestión de usuarios del sistema"
 * )
 */

class UsuarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/usuarios",
     *     operationId="getUsuarios",
     *     tags={"Usuarios"},
     *     summary="Listar usuarios",
     *     description="Lista usuarios según el rol del solicitante: admin ve todos, encargado su empresa, empleado solo a sí mismo",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de usuarios",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     security={{"sanctum":{}}}
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
     *     path="/api/usuarios/create",
     *     operationId="createUsuarioForm",
     *     tags={"Usuarios"},
     *     summary="Formulario para crear usuario",
     *     description="Retorna vista con formulario para crear un usuario (admin o encargado)",
     *     @OA\Response(response=200, description="Formulario mostrado"),
     *     security={{"sanctum":{}}}
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
     *     path="/api/usuarios",
     *     operationId="storeUsuario",
     *     tags={"Usuarios"},
     *     summary="Crear usuario",
     *     description="Crea un nuevo usuario. El encargado solo puede crear empleados en su empresa.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","rol_id","empresa_id"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
     *             @OA\Property(property="rol_id", type="integer", example=3),
     *             @OA\Property(property="empresa_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirección tras creación"),
     *     @OA\Response(response=422, description="Validación fallida"),
     *     security={{"sanctum":{}}}
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
    /**
     * @OA\Get(
     *     path="/api/usuarios/{usuario}",
     *     operationId="getUsuario",
     *     tags={"Usuarios"},
     *     summary="Obtener usuario",
     *     description="Retorna vista para editar un usuario",
     *     @OA\Parameter(
     *         name="usuario",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Usuario encontrado"),
     *     @OA\Response(response=404, description="No encontrado"),
     *     security={{"sanctum":{}}}
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
     *     path="/api/usuarios/{usuario}",
     *     operationId="updateUsuario",
     *     tags={"Usuarios"},
     *     summary="Actualizar usuario",
     *     description="Actualiza datos de un usuario. Encargado solo usuarios de su empresa.",
     *     @OA\Parameter(
     *         name="usuario",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","rol_id","empresa_id","activo"},
     *             @OA\Property(property="name", type="string", example="Nombre actualizado"),
     *             @OA\Property(property="email", type="string", format="email", example="nuevo@example.com"),
     *             @OA\Property(property="rol_id", type="integer", example=2),
     *             @OA\Property(property="empresa_id", type="integer", example=1),
     *             @OA\Property(property="activo", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirección tras actualización"),
     *     @OA\Response(response=422, description="Validación fallida"),
     *     security={{"sanctum":{}}}
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

        // AUDITORÍA: antes de actualizar
        $antes = $usuario->toArray();

        $usuario->update([
            'name'        => $request->name,
            'email'       => $request->email,
            'rol_id'      => $request->rol_id,
            'empresa_id'  => $request->empresa_id,
            'activo'      => $request->activo,
        ]);

        // AUDITORÍA: después de actualizar
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
    /**
     * @OA\Delete(
     *     path="/api/usuarios/{usuario}",
     *     operationId="deleteUsuario",
     *     tags={"Usuarios"},
     *     summary="Eliminar usuario",
     *     description="Elimina un usuario (encargado solo puede eliminar usuarios de su empresa)",
     *     @OA\Parameter(
     *         name="usuario",
     *         in="path",
     *         description="ID del usuario a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=302, description="Redirección tras eliminación"),
     *     @OA\Response(response=403, description="Permiso denegado"),
     *     security={{"sanctum":{}}}
     * )
     */
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

