<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_hora' => 'required|date',
            'precio_estimado' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Generar un código QR único de 9 caracteres
        $qrCode = Str::random(9);
        $validatedData = $validator->validated();
        $validatedData['qr_code'] = $qrCode;

        $cita = Cita::create($validatedData);

        return response()->json(['message' => 'Cita creada correctamente', 'cita' => $cita], 201);
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
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_hora' => 'required|date',
            'precio_estimado' => 'required|numeric',
            'qr_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $cita = Cita::findOrFail($id);
        $validatedData = $validator->validated();
        $cita->update($validatedData);

        return response()->json(['message' => 'Cita actualizada correctamente', 'cita' => $cita], 200);
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

        return response()->json(['message' => 'Cita eliminada correctamente'], 200);
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
