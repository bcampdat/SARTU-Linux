<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';

    public $timestamps = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'user_id',
        'accion',
        'detalle',
        'ip',
        'user_agent',
        'entidad_tipo',
        'entidad_id',
        'datos_antes',
        'datos_despues',
    ];

    protected $casts = [
        'datos_antes'   => 'array',
        'datos_despues' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }
}
