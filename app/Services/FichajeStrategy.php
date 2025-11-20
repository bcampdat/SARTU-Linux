<?php

namespace App\Services;

class FichajeContext
{
    protected $strategy;

    public function __construct($tipo)
    {
        $class = "App\\Services\\FichajeMethods\\" . ucfirst($tipo) . "Strategy";
        $this->strategy = new $class();
    }

    public function registrar($usuario, $data)
    {
        return $this->strategy->registrar($usuario, $data);
    }
}
