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

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }
}
