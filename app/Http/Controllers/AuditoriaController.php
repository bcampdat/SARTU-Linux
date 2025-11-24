<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;

class AuditoriaController extends Controller
{
    public function index()
    {
        return view('auditoria.index', [
            'logs' => Auditoria::with('usuario')->latest()->get()
        ]);
    }
}