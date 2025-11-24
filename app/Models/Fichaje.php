<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fichaje extends Model
{
    protected $table = 'fichajes';
    protected $primaryKey = 'id_fichaje';
    public $timestamps = true;

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'user_id',
        'metodo_id',
        'tipo',
        'fecha_hora',
        'lat',
        'lng',
        'notas'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    // Usuario relacionado
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    // Método de fichaje
    public function metodoFichaje()
    {
        return $this->belongsTo(MetodoFichaje::class, 'metodo_id', 'id_metodo');
    }
}
