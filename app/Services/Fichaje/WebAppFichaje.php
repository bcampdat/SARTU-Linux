<?php

namespace App\Services\Fichaje;

use App\Models\Fichaje;
use App\Models\Usuario;

class WebAppFichaje implements FichajeStrategyInterface
{
    public function fichar(Usuario $usuario, array $datos)
    {
        return Fichaje::create([
            'user_id'   => $usuario->id,
            'metodo_id' => 1, // WEB
            'tipo'      => $datos['tipo'],
            'fecha_hora'=> now(),
            'lat'       => $datos['lat'] ?? null,
            'lng'       => $datos['lng'] ?? null,
            'notas'     => $datos['notas'] ?? null,
        ]);
    }
}
