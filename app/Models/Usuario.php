<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

// sartu@sartu.com
// Pass123.

//jefe@sartu.com
// Sartu123. encargado

//empleado1@sartu.com
// Sartu123! empleado

// id, name, email, password, activation_token, estado, activo, fecha_registro, fecha_actualizacion, empresa_id, rol_id
//'2', 'Empresa1', 'jefe@sartu.com', '$2y$12$PWl.Aa3bm1oTVSfEEKa9NudbC9Ah03nyI7vJF31GcfcH6owjdzoCq', NULL, 'activo', '1', '2025-11-24 10:42:07', '2025-11-24 10:50:19', '1', '2'
//'3', 'Empleado1', 'empleado1@sartu.com', '$2y$12$vZ.Ks8gnbUR2TNvCDl0B5OqeGmVQChRcvSNV3GWf3.KmYAAEpjMT6', NULL, 'activo', '1', '2025-11-24 11:12:02', '2025-11-24 11:15:13', NULL, '3'


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
        'empresa_id',
        'rol_id',
        'name',
        'email',
        'password',
        'activo',
        'activation_token',
        'estado'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id_empresa');
    }
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id_rol');
    }
    public function fichajes()
    {
        return $this->hasMany(Fichaje::class, 'user_id', 'id');
    }
}
