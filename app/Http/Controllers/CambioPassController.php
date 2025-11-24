<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CambioPassController extends Controller
{
    public function showForm()
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if ($user->estado === 'activo') {
            return redirect()->route('dashboard');
        }

        return view('auth.force-change-password');
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if ($user->estado !== 'pendiente') {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
        ]);

        $user->password = Hash::make($request->password);
        $user->estado = 'activo';
        $user->activo = 1;
        $user->save();  

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada correctamente.');
    }
}
