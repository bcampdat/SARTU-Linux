<?php

namespace App\Services;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;

//AuditoriaService::log('login_correcto', 'Usuario', $user->id, null, null, 'Login desde web');  asi  se inyecta

class AuditoriaService
{
    public static function log(
        string $accion,
        ?string $entidadTipo = null,
        ?int $entidadId = null,
        $antes = null,
        $despues = null,
        ?string $detalle = null
    ): void {
        $user = Auth::user();

        Auditoria::create([
            'user_id'      => $user?->id,
            'accion'       => $accion,
            'detalle'      => $detalle,
            'ip'           => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'entidad_tipo' => $entidadTipo,
            'entidad_id'   => $entidadId,
            'datos_antes'  => $antes,
            'datos_despues'=> $despues,
        ]);
    }
}
