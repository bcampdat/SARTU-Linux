<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';

    public $timestamps = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre',
        'limite_usuarios',
        'jornada_diaria_minutos',
        'max_pausa_no_contabilizada',
        
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'empresa_id', 'id_empresa');
    }
}
