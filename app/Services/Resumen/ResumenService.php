<?php

namespace App\Services\Resumen;

use App\Models\Fichaje;
use App\Models\ResumenDiario;
use App\Models\Usuario;

class ResumenService
{
    public static function recalcular(int $userId, string $fecha): void
    {
        $usuario = Usuario::with('empresa')->findOrFail($userId);
        $empresa = $usuario->empresa;

        $jornadaMin     = $empresa->jornada_diaria_minutos ?? 480;
        $maxPausaLibre  = $empresa->max_pausa_no_contabilizada ?? 0;

        $fichajes = Fichaje::where('user_id', $userId)
            ->whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora')
            ->get();

        $trabajo = 0;
        $pausas  = 0;

        $prevTipo   = null;
        $prevHora   = null;

        foreach ($fichajes as $f) {

            if ($prevTipo !== null) {

                $mins = $prevHora->diffInMinutes($f->fecha_hora);

                if (
                    in_array($prevTipo, ['entrada', 'reanudar']) &&
                    in_array($f->tipo, ['pausa', 'salida'])
                ) {
                    $trabajo += $mins;
                }

                if (
                    $prevTipo === 'pausa' &&
                    in_array($f->tipo, ['reanudar', 'salida'])
                ) {
                    if ($mins > $maxPausaLibre) {
                        $pausas += $mins;
                    }
                }
            }

            $prevTipo = $f->tipo;
            $prevHora = $f->fecha_hora;
        }

        ResumenDiario::updateOrCreate(
            ['user_id' => $userId, 'fecha' => $fecha],
            [
                'tiempo_trabajado' => $trabajo,
                'tiempo_pausas'    => $pausas,
                'tiempo_total'     => $trabajo + $pausas,
            ]
        );
    }
}
