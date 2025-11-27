<?php
namespace App\Services\Fichaje;

class FichajeFactory
{
    public static function make(string $metodo): FichajeStrategyInterface
    {
        return match ($metodo) {
            'web'  => new WebAppFichaje(),
            'nfc'  => new NfcFichaje(),
            'pc'   => new PcAutologFichaje(),
            'pwa'  => new PwaMovilFichaje(),
            default => throw new \Exception("Método de fichaje no válido")
        };
    }
}
