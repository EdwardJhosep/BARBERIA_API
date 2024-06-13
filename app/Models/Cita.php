<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cita extends Model
{
    protected $fillable = [
        'client_id', 'servicio_id', 'empleado_id', 'fecha_hora', 'precio_estimado', 'codigo_unico', 'qr_code',
    ];

    // Generar automáticamente el código único al crear una nueva cita
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cita) {
            $cita->codigo_unico = Str::uuid();
        });
    }

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'client_id');
    }

    // Relación con servicio
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    // Relación con empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    // Buscar cita por código único
    public function scopePorCodigo($query, $codigo)
    {
        return $query->where('codigo_unico', $codigo);
    }
}
