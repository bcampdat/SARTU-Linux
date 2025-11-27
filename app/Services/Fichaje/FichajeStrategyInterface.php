<?php

namespace App\Services\Fichaje;

use App\Models\Usuario;

interface FichajeStrategyInterface
{
    public function fichar(Usuario $usuario, array $datos);
}
