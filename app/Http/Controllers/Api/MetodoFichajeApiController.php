<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MetodoFichaje;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class MetodoFichajeApiController extends Controller
{
    public function index()
    {
        return response()->json(
            MetodoFichaje::all()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:metodos_fichaje'
        ]);

        $metodo = MetodoFichaje::create([
            'nombre' => $request->nombre,
            'slug'   => $request->slug ?? Str::slug($request->nombre)
        ]);

        return response()->json($metodo, 201);
    }

    public function show(MetodoFichaje $metodo)
    {
        return response()->json($metodo);
    }

    public function destroy(MetodoFichaje $metodo)
    {
        try {
            $metodo->delete();
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'No se puede eliminar el método (está en uso)'
            ], 409);
        }

        return response()->json(['ok' => true]);
    }
}
