<?php

namespace App\Http\Controllers;

use App\Models\MetodoFichaje;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *   name="MetodoFichaje",
 *   description="Métodos de fichaje (web)"
 * )
 */
class MetodoFichajeController extends Controller
{

    /**
     * @OA\Get(
     *   path="/metodos-fichaje",
     *   tags={"MetodoFichaje"},
     *   summary="Lista de métodos de fichaje (vista)",
     *   @OA\Response(response=200, description="HTML view")
     * )
     */
    public function index() {
        return view('metodos.index', ['metodos' => MetodoFichaje::all()]);
    }

    /**
     * @OA\Post(
     *   path="/metodos-fichaje",
     *   tags={"MetodoFichaje"},
     *   summary="Crear método de fichaje (form submission)",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *         @OA\Property(property="nombre", type="string", example="web")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=302, description="Redirect (HTML)"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|unique:metodos_fichaje'
        ]);

        MetodoFichaje::create($request->all());

        return back()->with('success', 'Método creado');
    }
}
