<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    /**
     * Mostrar todos los empleados
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleados = Empleado::all();
        return response()->json($empleados);
    }

    /**
     * Mostrar un empleado específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        return response()->json($empleado);
    }

    /**
     * Crear un nuevo empleado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string',
            'telefono' => 'required|string',
            'tipo' => 'required|string',
            'foto' => 'nullable|image',
        ]);

        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('empleados', 'public');
        }

        $empleado = Empleado::create($validatedData);

        return response()->json($empleado, 201);
    }

    /**
     * Actualizar un empleado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:empleados,id',
            'nombre' => 'required|string',
            'telefono' => 'required|string',
            'tipo' => 'required|string',
            'foto' => 'nullable|image',
        ]);
    
        $empleado = Empleado::findOrFail($validatedData['id']);
    
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($empleado->foto) {
                Storage::disk('public')->delete($empleado->foto);
            }
            // Guardar la nueva foto
            $path = $request->file('foto')->move(public_path('empleados'), $request->file('foto')->getClientOriginalName());
            $validatedData['foto'] = 'empleados/' . $request->file('foto')->getClientOriginalName();
        }
    
        $empleado->update([
            'nombre' => $validatedData['nombre'],
            'telefono' => $validatedData['telefono'],
            'tipo' => $validatedData['tipo'],
            'foto' => $validatedData['foto'] ?? $empleado->foto, // mantener la foto existente si no se sube una nueva
        ]);
    
        return response()->json($empleado, 200);
    }

    /**
     * Eliminar un empleado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);

        // Eliminar la foto del empleado si existe
        if ($empleado->foto) {
            Storage::disk('public')->delete($empleado->foto);
        }

        $empleado->delete();

        return response()->json(['message' => 'Empleado eliminado correctamente'], 200);
    }
}
