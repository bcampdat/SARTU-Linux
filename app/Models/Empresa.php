<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'limite_usuarios'
    ];

    // Relación con Usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_empresa', 'id_empresa');
    }
}