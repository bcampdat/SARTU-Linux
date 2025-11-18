<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoFichaje extends Model
{
    protected $table = 'metodos_fichaje';
    protected $primaryKey = 'id_metodo';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    // Relación con Fichajes
    public function fichajes()
    {
        return $this->hasMany(Fichaje::class, 'id_metodo', 'id_metodo');
    }
}