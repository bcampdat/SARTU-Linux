<?php

namespace App\Services\Resumen;

use App\Models\Fichaje;
use App\Models\ResumenDiario;
use App\Models\Usuario;

class ResumenService
{
    public static function recalcular(int $userId, string $fecha): void
    {
        
        /** @var \App\Models\Usuario $usuario */
        $usuario = Usuario::with('empresa')->findOrFail($userId);
        $empresa = $usuario->empresa;

        $jornadaMinutos = $empresa->jornada_diaria_minutos ?? 480; // por defecto 8h

        $fichajes = Fichaje::where('user_id', $userId)
            ->whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora')
            ->get();

        $tiempoTrabajo = 0;
        $tiempoPausas  = 0;

        $ultimoTipo   = null;
        $ultimoTiempo = null;

        foreach ($fichajes as $f) {

            if ($ultimoTipo === null) {
                $ultimoTipo   = $f->tipo;
                $ultimoTiempo = $f->fecha_hora;
                continue;
            }

            //  TRAMOS DE TRABAJO
            if (
                in_array($ultimoTipo, ['entrada', 'reanudar']) &&
                in_array($f->tipo, ['pausa', 'salida'])
            ) {
                $tiempoTrabajo += $ultimoTiempo->diffInMinutes($f->fecha_hora);
            }

            //  TRAMOS DE PAUSA
            if (
                $ultimoTipo === 'pausa' &&
                in_array($f->tipo, ['reanudar', 'salida'])
            ) {
                $tiempoPausas += $ultimoTiempo->diffInMinutes($f->fecha_hora);
            }

            $ultimoTipo   = $f->tipo;
            $ultimoTiempo = $f->fecha_hora;
        }

        // % de jornada completada
        $porcentaje = 0;
        if ($jornadaMinutos > 0) {
            $porcentaje = min(100, round(($tiempoTrabajo / $jornadaMinutos) * 100));
        }

        ResumenDiario::updateOrCreate(
            ['user_id' => $userId, 'fecha' => $fecha],
            [
                'tiempo_trabajado' => $tiempoTrabajo,
                'tiempo_pausas'    => $tiempoPausas,
                'tiempo_total'     => $tiempoTrabajo + $tiempoPausas,
                
                // 'jornada_minutos'  => $jornadaMinutos,
                // 'progreso_jornada' => $porcentaje,
            ]
        );
    }
}
