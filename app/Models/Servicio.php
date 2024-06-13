<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'foto1', 'foto2', 'foto3',
    ];

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'servicio_id');
    }
}
