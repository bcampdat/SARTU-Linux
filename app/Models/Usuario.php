<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_empresa',
        'id_rol',
        'nombre',
        'email',
        'password', 
        'activo'
    ];

    protected $hidden = [
        'password_hash',
    ];

    // Mutator para encriptar password
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = bcrypt($value);
    }

    // Laravel Auth: devuelve la columna real
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relaciones
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
        return $this->hasMany(Fichaje::class, 'id_usuario', 'id_usuario');
    }
}
