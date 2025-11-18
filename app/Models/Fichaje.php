<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fichaje extends Model
{
    protected $table = 'fichajes';
    protected $primaryKey = 'id_fichaje';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'id_metodo',
        'tipo',
        'fecha_hora',
        'lat',
        'lng',
        'notas'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7'
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Relación con Método de Fichaje
    public function metodoFichaje()
    {
        return $this->belongsTo(MetodoFichaje::class, 'id_metodo', 'id_metodo');
    }
}