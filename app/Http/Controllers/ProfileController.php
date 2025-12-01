<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Services\AuditoriaService;

class ProfileController extends Controller
{
    /**
     * Mostrar formulario de perfil
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualizar perfil
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        //  Bloqueados y pendientes NO pueden modificar perfil
        if (in_array($user->estado, ['bloqueado', 'pendiente'])) {
            abort(403, 'Tu cuenta no puede modificarse en este estado.');
        }

        // Guardamos estado anterior para auditoría
        $antes = $user->toArray();

        //  Validar avatar (solo aquí)
        $request->validate([
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        //  Solo permitimos actualizar nombre (NO email aquí)
        $datos = $request->validated();
        unset($datos['email']); // Blindaje total

        $user->fill($datos);

        // Procesar avatar si existe
        if ($request->hasFile('avatar')) {

            // Borrar avatar anterior si existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            try {
                $file = $request->file('avatar');

                $manager = new ImageManager(new Driver());
                $image   = $manager->read($file->getPathname());
                $image->scale(width: 300);

                $avatarBinary = $image->toJpeg(85);
                $avatarName   = 'avatars/avatar_' . uniqid() . '.jpg';

                Storage::disk('public')->put($avatarName, $avatarBinary);

                $user->avatar = $avatarName;
            } catch (\Throwable $e) {
                return back()->with('error', 'Error al procesar la imagen de perfil.');
            }
        }

        $user->save();

        //  Auditoría 
        AuditoriaService::log(
            'perfil_actualizado',
            'Usuario',
            $user->id,
            $antes,
            $user->toArray(),
            'Actualización de perfil'
        );

        return Redirect::route('dashboard')
            ->with('status', 'profile-updated');
    }

    /**
     * Eliminar cuenta
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Guardar estado anterior para auditoría
        $antes = $user->toArray();

        //  BLOQUEO LÓGICO (NO SE BORRA)
        $user->estado = 'bloqueado';
        $user->activo = 0;
        $user->save();

        // Auditoría
        AuditoriaService::log(
            'bloqueo_propio_usuario',
            'Usuario',
            $user->id,
            $antes,
            $user->toArray(),
            'Usuario se ha bloqueado a sí mismo desde perfil'
        );

        // Logout forzado
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')
            ->with('status', 'account-blocked');
    }
}
