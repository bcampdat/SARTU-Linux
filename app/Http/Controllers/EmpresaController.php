<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Mostrar listado de empresas
     */
    public function index()
    {
        $empresas = Empresa::all();
        return view('empresas.list', compact('empresas'));
    }

    /**
     * Mostrar formulario para crear una nueva empresa
     */
    public function create()
    {
        $empresa = new Empresa(); // objeto vacío para el form
        $action = route('empresas.store');
        $method = 'POST';
        return view('empresas.form', compact('empresa', 'action', 'method'));
    }

    /**
     * Guardar nueva empresa
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'limite_usuarios' => 'required|integer|min:1'
        ]);

        Empresa::create($request->all());
        return redirect()->route('empresas.index')->with('success', 'Empresa creada');
    }

    /**
     * Mostrar detalle de empresa
     */
    public function show(Empresa $empresa)
    {
        return view('empresas.list', compact('empresa')); // puedes usar la misma vista list para detalle
    }

    /**
     * Mostrar formulario para editar empresa
     */
    public function edit(Empresa $empresa)
    {
        $action = route('empresas.update', $empresa->id_empresa);
        $method = 'PUT';
        return view('empresas.form', compact('empresa', 'action', 'method'));
    }

    /**
     * Actualizar empresa
     */
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nombre' => 'required',
            'limite_usuarios' => 'required|integer|min:1'
        ]);

        $empresa->update($request->all());
        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada');
    }

    /**
     * Eliminar empresa
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada');
    }
}
