<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Mostrar todas las citas
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $citas = Cita::with('cliente', 'servicio', 'empleado')->get();
        return response()->json($citas);
    }

    /**
     * Mostrar una cita específica
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cita = Cita::with('cliente', 'servicio', 'empleado')->findOrFail($id);
        return response()->json($cita);
    }

    /**
     * Crear una nueva cita
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_hora' => 'required|date',
            'precio_estimado' => 'required|numeric',
            // 'codigo_unico' => 'nullable|string', // No es necesario incluirlo aquí
            'qr_code' => 'nullable|string',
        ]);

        $cita = Cita::create($validatedData);

        return response()->json($cita, 201);
    }

    /**
     * Actualizar una cita
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_hora' => 'required|date',
            'precio_estimado' => 'required|numeric',
            // 'codigo_unico' => 'nullable|string', // No es necesario incluirlo aquí
            'qr_code' => 'nullable|string',
        ]);

        $cita = Cita::findOrFail($id);
        $cita->update($validatedData);

        return response()->json($cita, 200);
    }

    /**
     * Eliminar una cita
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return response()->json(null, 204);
    }

    /**
     * Buscar cita por código único
     *
     * @param  string  $codigo
     * @return \Illuminate\Http\Response
     */
    public function buscarPorCodigo($codigo)
    {
        $cita = Cita::with('cliente', 'servicio', 'empleado')->porCodigo($codigo)->firstOrFail();
        return response()->json($cita);
    }
}
