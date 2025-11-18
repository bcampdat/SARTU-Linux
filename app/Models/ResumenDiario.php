<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumenDiario extends Model
{
    protected $table = 'resumen_diario';
    protected $primaryKey = 'id_resumen';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'fecha',
        'tiempo_trabajado',
        'tiempo_pausas', 
        'tiempo_total'
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}