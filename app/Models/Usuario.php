<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_empresa',
        'id_rol',
        'name',
        'email',
        'password',
        'activo'
    ];

    protected $hidden = [
        'password',
    ];

    // ELIMINADO: Los mutators ya no son necesarios
    // Laravel maneja automáticamente 'password'

    // Relaciones (MANTIENE igual)
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function fichajes()
    {
        return $this->hasMany(Fichaje::class, 'id_usuario', 'id');
    }
}
