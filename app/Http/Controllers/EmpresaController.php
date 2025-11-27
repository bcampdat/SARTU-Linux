<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *   name="Empresa",
 *   description="Operaciones sobre empresas (web forms)"
 * )
 */

class EmpresaController extends Controller
{

    /**
     * @OA\Get(
     *   path="/empresa",
     *   tags={"Empresa"},
     *   summary="Listado de empresas (vista)",
     *   @OA\Response(response=200, description="HTML view")
     * )
     */
    public function index()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('empresa.index', compact('empresas'));
    }

    /**
     * @OA\Get(
     *   path="/empresa/create",
     *   tags={"Empresa"},
     *   summary="Formulario creación empresa",
     *   @OA\Response(response=200, description="HTML view")
     * )
     */
    public function create()
    {
        return view('empresa.create');
    }

    /**
     * @OA\Post(
     *   path="/empresa",
     *   tags={"Empresa"},
     *   summary="Crear empresa (form submission)",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *         @OA\Property(property="nombre", type="string"),
     *         @OA\Property(property="limite_usuarios", type="integer"),
     *         @OA\Property(property="jornada_diaria_minutos", type="integer"),
     *         @OA\Property(property="max_pausa_no_contabilizada", type="integer")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=302, description="Redirect (HTML)")
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'limite_usuarios' => 'required|integer|min:1',
            'jornada_diaria_minutos' => 'required|integer|min:1',
            'max_pausa_no_contabilizada' => 'required|integer|min:0',
        ]);

        Empresa::create([
            'nombre' => strip_tags($request->nombre),
            'limite_usuarios' => $request->limite_usuarios,
            'jornada_diaria_minutos' => $request->jornada_diaria_minutos,
            'max_pausa_no_contabilizada' => $request->max_pausa_no_contabilizada,
        ]);

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    /**
     * @OA\Get(
     *   path="/empresa/{empresa}/edit",
     *   tags={"Empresa"},
     *   summary="Editar empresa (formulario)",
     *   @OA\Parameter(
     *     name="empresa",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="HTML view")
     * )
     */

    public function edit(Empresa $empresa)
    {
        return view('empresa.edit', compact('empresa'));
    }

    /**
     * @OA\Put(
     *   path="/empresa/{empresa}",
     *   tags={"Empresa"},
     *   summary="Actualizar empresa (form submission)",
     *   @OA\Parameter(
     *     name="empresa",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *         @OA\Property(property="nombre", type="string"),
     *         @OA\Property(property="limite_usuarios", type="integer"),
     *         @OA\Property(property="jornada_diaria_minutos", type="integer"),
     *         @OA\Property(property="max_pausa_no_contabilizada", type="integer")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=302, description="Redirect (HTML)")
     * )
     */
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'limite_usuarios' => 'required|integer|min:1',
            'jornada_diaria_minutos' => 'required|integer|min:1',
            'max_pausa_no_contabilizada' => 'required|integer|min:0',
        ]);

        $empresa->update([
            'nombre' => strip_tags($request->nombre),
            'limite_usuarios' => $request->limite_usuarios,
            'jornada_diaria_minutos' => $request->jornada_diaria_minutos,
            'max_pausa_no_contabilizada' => $request->max_pausa_no_contabilizada,
        ]);

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    /**
     * @OA\Delete(
     *   path="/empresa/{empresa}",
     *   tags={"Empresa"},
     *   summary="Eliminar empresa",
     *   @OA\Parameter(
     *     name="empresa",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=302, description="Redirect (HTML)")
     * )
     */

    public function destroy(Empresa $empresa)
    {
        if ($empresa->usuarios()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una empresa con usuarios asociados.');
        }

        $empresa->delete();

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa eliminada.');
    }
}
