<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Services\AuditoriaService;


/**
 * @OA\Tag(
 *     name="Empresas",
 *     description="Gestión de empresas"
 * )
 */

class EmpresaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/empresas",
     *     operationId="getEmpresas",
     *     tags={"Empresas"},
     *     summary="Listar empresas",
     *     description="Obtiene la lista de empresas ordenadas por nombre",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de empresas",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function index()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('empresa.index', compact('empresas'));
    }

    /**
     * @OA\Get(
     *     path="/api/empresas",
     *     operationId="getEmpresas",
     *     tags={"Empresas"},
     *     summary="Listar empresas",
     *     description="Obtiene la lista de empresas ordenadas por nombre",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de empresas",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function create()
    {
        return view('empresa.create');
    }

    /**
     * @OA\Post(
     *     path="/api/empresas",
     *     operationId="storeEmpresa",
     *     tags={"Empresas"},
     *     summary="Crear nueva empresa",
     *     description="Crea una nueva empresa. Campos: nombre, limite_usuarios, jornada_diaria_minutos, max_pausa_no_contabilizada",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","limite_usuarios","jornada_diaria_minutos","max_pausa_no_contabilizada"},
     *             @OA\Property(property="nombre", type="string", example="Mi Empresa"),
     *             @OA\Property(property="limite_usuarios", type="integer", example=50),
     *             @OA\Property(property="jornada_diaria_minutos", type="integer", example=480),
     *             @OA\Property(property="max_pausa_no_contabilizada", type="integer", example=15)
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirección tras creación exitosa"),
     *     @OA\Response(response=422, description="Validación fallida"),
     *     security={{"sanctum":{}}}
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

        $empresa = Empresa::create([
            'nombre' => strip_tags($request->nombre),
            'limite_usuarios' => $request->limite_usuarios,
            'jornada_diaria_minutos' => $request->jornada_diaria_minutos,
            'max_pausa_no_contabilizada' => $request->max_pausa_no_contabilizada,
        ]);

        
        AuditoriaService::log(
            'crear_empresa',
            'Empresa',
            $empresa->id_empresa,
            null,
            $empresa->toArray(),
            'Empresa creada'
        );

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    /**
     * @OA\Get(
     *     path="/api/empresas/{empresa}",
     *     operationId="getEmpresa",
     *     tags={"Empresas"},
     *     summary="Obtener empresa",
     *     description="Retorna la vista para editar una empresa (detalle de la empresa)",
     *     @OA\Parameter(
     *         name="empresa",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Empresa encontrada"),
     *     @OA\Response(response=404, description="No encontrada"),
     *     security={{"sanctum":{}}}
     * )
     */
    public function edit(Empresa $empresa)
    {
        return view('empresa.edit', compact('empresa'));
    }

    /**
     * @OA\Put(
     *     path="/api/empresas/{empresa}",
     *     operationId="updateEmpresa",
     *     tags={"Empresas"},
     *     summary="Actualizar empresa",
     *     description="Actualiza los datos de una empresa existente",
     *     @OA\Parameter(
     *         name="empresa",
     *         in="path",
     *         description="ID de la empresa a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","limite_usuarios","jornada_diaria_minutos","max_pausa_no_contabilizada"},
     *             @OA\Property(property="nombre", type="string", example="Mi Empresa Actualizada"),
     *             @OA\Property(property="limite_usuarios", type="integer", example=100),
     *             @OA\Property(property="jornada_diaria_minutos", type="integer", example=450),
     *             @OA\Property(property="max_pausa_no_contabilizada", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirección tras actualización"),
     *     @OA\Response(response=422, description="Validación fallida"),
     *     security={{"sanctum":{}}}
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

        
        $antes = $empresa->toArray();

        $empresa->update([
            'nombre' => strip_tags($request->nombre),
            'limite_usuarios' => $request->limite_usuarios,
            'jornada_diaria_minutos' => $request->jornada_diaria_minutos,
            'max_pausa_no_contabilizada' => $request->max_pausa_no_contabilizada,
        ]);

        
        AuditoriaService::log(
            'editar_empresa',
            'Empresa',
            $empresa->id_empresa,
            $antes,
            $empresa->toArray(),
            'Empresa actualizada'
        );

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    /**
     * @OA\Delete(
     *     path="/api/empresas/{empresa}",
     *     operationId="deleteEmpresa",
     *     tags={"Empresas"},
     *     summary="Eliminar empresa",
     *     description="Elimina una empresa si no tiene usuarios asociados",
     *     @OA\Parameter(
     *         name="empresa",
     *         in="path",
     *         description="ID de la empresa a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=302, description="Redirección tras eliminación"),
     *     @OA\Response(response=403, description="No se puede eliminar si tiene usuarios asociados"),
     *     security={{"sanctum":{}}}
     * )
     */
    public function destroy(Empresa $empresa)
    {
        if ($empresa->usuarios()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una empresa con usuarios asociados.');
        }

        
        AuditoriaService::log(
            'eliminar_empresa',
            'Empresa',
            $empresa->id_empresa,
            $empresa->toArray(),
            null,
            'Empresa eliminada'
        );

        $empresa->delete();

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa eliminada.');
    }
}
