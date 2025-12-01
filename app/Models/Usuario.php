<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// sartu@sartu.com
// Pass123.

class Usuario extends Authenticatable
{
     use HasApiTokens, Notifiable;

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
        return $this->belongsTo(Rol::class, 'rol_id', 'id_rol')
            ->select('id_rol', 'nombre');
    }
    public function fichajes()
    {
        return $this->hasMany(Fichaje::class, 'user_id', 'id');
    }
}
