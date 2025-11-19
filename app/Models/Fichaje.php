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
        'id_usuario',
        'id_metodo',
        'tipo',
        'timestamp', 
        'lat',
        'lng',
        'notas'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function metodoFichaje()
    {
        return $this->belongsTo(MetodoFichaje::class, 'id_metodo', 'id_metodo');
    }
}
