<?php

namespace App\Services\Fichaje;

use App\Models\Fichaje;
use App\Models\Usuario;

class PcAutologFichaje implements FichajeStrategyInterface
{
    public function fichar(Usuario $usuario, array $datos)
    {
        return Fichaje::create([
            'user_id'   => $usuario->id,
            'metodo_id' => 4, // PC
            'tipo'      => $datos['tipo'],
            'fecha_hora'=> now(),
            'lat'       => null,
            'lng'       => null,
            'notas'     => 'Autolog PC',
        ]);
    }
}
