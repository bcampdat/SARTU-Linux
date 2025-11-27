<?php

namespace App\Services\Fichaje;

use App\Models\Fichaje;
use App\Models\Usuario;

class NfcFichaje implements FichajeStrategyInterface
{
    public function fichar(Usuario $usuario, array $datos)
    {
        return Fichaje::create([
            'user_id'   => $usuario->id,
            'metodo_id' => 3, // NFC
            'tipo'      => $datos['tipo'],
            'fecha_hora'=> now(),
            'lat'       => null,
            'lng'       => null,
            'notas'     => 'Fichaje vía NFC',
        ]);
    }
}
