<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumenDiario extends Model
{
    protected $table = 'resumen_diario';
    protected $primaryKey = 'id_resumen';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'fecha',
        'tiempo_trabajado',
        'tiempo_pausas',
        'tiempo_total'
    ];

     protected $appends = [
        'alerta_pausa',
        'alerta_jornada',
    ];

    public function getAlertaPausaAttribute()
    {
        $empresa = $this->usuario->empresa ?? null;
        if (!$empresa) return false;

        return $this->tiempo_pausas > $empresa->max_pausa_no_contabilizada;
    }

    public function getAlertaJornadaAttribute()
    {
        $empresa = $this->usuario->empresa ?? null;
        if (!$empresa) return false;

        return $this->tiempo_trabajado > $empresa->jornada_diaria_minutos;
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

}

