<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'id_empresa',
        'id_rol', 
        'nombre',
        'email',
        'password',
        'activo'
    ];

    protected $hidden = [
        'password',
    ];

    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    // Relación con Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Relación con Fichajes
    public function fichajes()
    {
        return $this->hasMany(Fichaje::class, 'id_usuario', 'id_usuario');
    }
}