<?php

namespace App\Http\Controllers;

use App\Models\MetodoFichaje;
use Illuminate\Http\Request;

class MetodoFichajeController extends Controller
{
    public function index() {
        return view('metodos.index', ['metodos' => MetodoFichaje::all()]);
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|unique:metodos_fichaje'
        ]);

        MetodoFichaje::create($request->all());

        return back()->with('success', 'Método creado');
    }
}