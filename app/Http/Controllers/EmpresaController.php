<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Services\AuditoriaService;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('empresa.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresa.create');
    }

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

    public function edit(Empresa $empresa)
    {
        return view('empresa.edit', compact('empresa'));
    }

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
