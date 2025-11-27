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

        $maxPausaLibre = $empresa->max_pausa_no_contabilizada ?? 0;

        $fichajes = Fichaje::where('user_id', $userId)
            ->whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora')
            ->get();

        $trabajo = 0;
        $pausas  = 0;

        $prevTipo = null;
        $prevHora = null;

        foreach ($fichajes as $f) {

            if ($prevTipo !== null) {
                $mins = $prevHora->diffInMinutes($f->fecha_hora);

                // TRABAJO
                if (
                    in_array($prevTipo, ['entrada', 'reanudar']) &&
                    in_array($f->tipo, ['pausa', 'salida'])
                ) {
                    $trabajo += $mins;
                }

                // PAUSA (SE SUMA SIEMPRE)
                if ($prevTipo === 'pausa' && in_array($f->tipo, ['reanudar', 'salida'])) {
                    $pausas += $mins;
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
