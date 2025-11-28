<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\AuditoriaService;


/**
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Gestión de sesiones y autenticación de usuarios"
 * )
 */

class AuthenticatedSessionController extends Controller
{
     /**
     * Display the login view.
     *
     * @OA\Get(
     *     path="/login",
     *     operationId="showLoginForm",
     *     tags={"Autenticación"},
     *     summary="Mostrar formulario de login",
     *     description="Retorna la vista del formulario de inicio de sesión",
     *     @OA\Response(
     *         response=200,
     *         description="Formulario de login mostrado exitosamente"
     *     )
     * )
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @OA\Post(
     *     path="/login",
     *     operationId="login",
     *     tags={"Autenticación"},
     *     summary="Iniciar sesión",
     *     description="Autentica un usuario con email y contraseña. Registra intentos de login en auditoría",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciales del usuario",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="remember", type="boolean", example=false, description="Recordar sesión")
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirección exitosa al dashboard o cambio de contraseña"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida - email o contraseña incorrectos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        //  LOGIN FALLIDO
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {

            AuditoriaService::log(
                'login_fallido',
                'Usuario',
                null,
                null,
                ['email' => $request->email],
                'Intento de acceso fallido'
            );

            throw ValidationException::withMessages([
                'email' => 'El correo o la contraseña no son correctos.',
            ]);
        }

        $user = Auth::user();

        //  LOGIN CORRECTO
        AuditoriaService::log(
            'login_correcto',
            'Usuario',
            $user->id,
            null,
            null,
            'Inicio de sesión correcto'
        );

        // SUPERADMIN: acceso directo
        if ($user->rol->nombre === 'admin_sistema') {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Usuario BLOQUEADO
        if ($user->estado === 'bloqueado') {

            AuditoriaService::log(
                'login_bloqueado',
                'Usuario',
                $user->id,
                null,
                null,
                'Intento de acceso con cuenta bloqueada'
            );

            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Tu cuenta está bloqueada. Contacta con tu empresa.',
            ]);
        }

        // Usuario pendiente → obligar a cambiar la contraseña
        if ($user->estado === 'pendiente') {

            AuditoriaService::log(
                'login_pendiente',
                'Usuario',
                $user->id,
                null,
                null,
                'Acceso inicial con contraseña temporal'
            );

            $request->session()->regenerate();
            return redirect()->route('password.force-change');
        }

        // Estado normal (activo)
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     *
     * @OA\Post(
     *     path="/logout",
     *     operationId="logout",
     *     tags={"Autenticación"},
     *     summary="Cerrar sesión",
     *     description="Cierra la sesión del usuario autenticado y registra la acción en auditoría",
     *     @OA\Response(
     *         response=302,
     *         description="Redirección exitosa a página de inicio"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado"
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function destroy(Request $request): RedirectResponse
    {
        //  LOGOUT
        AuditoriaService::log(
            'logout',
            'Usuario',
            Auth::id(),
            null,
            null,
            'Cierre de sesión'
        );

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
