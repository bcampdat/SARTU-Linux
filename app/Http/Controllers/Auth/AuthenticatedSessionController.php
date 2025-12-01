<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\AuditoriaService;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
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
