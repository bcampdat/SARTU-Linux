<?php

namespace App\Services\Fichaje;

class FichajeFactory
{
    public static function make(string $metodo): FichajeStrategyInterface
    {
        $existe = \App\Models\MetodoFichaje::where('slug', $metodo)->exists();
        
        if (! $existe) {
            throw new \Exception("Método no registrado en BD");
        }

        return match ($metodo) {
            'web'  => new WebAppFichaje(),
            'nfc'  => new NfcFichaje(),
            'pc'   => new PcAutologFichaje(),
            'pwa'  => new PwaMovilFichaje(),
        };
    }
}
