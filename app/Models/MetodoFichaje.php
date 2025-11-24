<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoFichaje extends Model
{
    protected $table = 'metodos_fichaje';
    protected $primaryKey = 'id_metodo';

    public $timestamps = true;
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function fichajes()
    {
        return $this->hasMany(Fichaje::class, 'metodo_id', 'id_metodo');
    }
}
