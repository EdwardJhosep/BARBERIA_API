<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre', 'telefono', 'email', 'password', 'genero', 'foto',
    ];

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'client_id');
    }

    // Relación con notificaciones
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'cliente_id');
    }
}
