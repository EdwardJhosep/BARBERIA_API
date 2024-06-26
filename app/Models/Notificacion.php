<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones'; // Especificar el nombre correcto de la tabla

    protected $fillable = [
        'cliente_id', 'mensaje', 'fecha_envio', 'leido',
    ];

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
