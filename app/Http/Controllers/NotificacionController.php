<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Mostrar todas las notificaciones
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = Notificacion::with('cliente')->get();
        return response()->json($notificaciones);
    }

    /**
     * Mostrar una notificación específica
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notificacion = Notificacion::with('cliente')->findOrFail($id);
        return response()->json($notificacion);
    }

    /**
     * Crear una nueva notificación
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'mensaje' => 'required|string',
            'fecha_envio' => 'required|date',
            'leido' => 'required|boolean',
        ]);

        $notificacion = Notificacion::create($validatedData);

        return response()->json($notificacion, 201);
    }

    /**
     * Actualizar una notificación
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'mensaje' => 'required|string',
            'fecha_envio' => 'required|date',
            'leido' => 'required|boolean',
        ]);

        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update($validatedData);

        return response()->json($notificacion, 200);
    }

    /**
     * Eliminar una notificación
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->delete();

        return response()->json(null, 204);
    }
}
