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
        // Ajusta la URL de la imagen para que sea accesible desde el navegador
        foreach ($empleados as $empleado) {
            $empleado->foto = asset($empleado->foto);
        }
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
        // Validación de los datos de entrada
        $validatedData = $request->validate([
            'nombre' => 'required|string',
            'telefono' => 'required|string',
            'tipo' => 'required|string',
            'foto' => 'nullable|image',
        ]);
    
        // Manejar la carga del archivo si hay una foto presente en la solicitud
        if ($request->hasFile('foto')) {
            // Guardar la imagen directamente en public/empleados
            $fileName = $request->file('foto')->getClientOriginalName();
            $path = $request->file('foto')->move(public_path('empleados'), $fileName);
            
            // Asignar la ruta relativa al campo 'foto' validado
            $validatedData['foto'] = 'empleados/' . $fileName;
        }
    
        // Crear un nuevo Empleado utilizando los datos validados
        $empleado = Empleado::create($validatedData);
    
        // Retornar una respuesta JSON indicando éxito
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
        // Validación de los datos de entrada
        $validatedData = $request->validate([
            'id' => 'required|exists:empleados,id',
            'nombre' => 'required|string',
            'telefono' => 'required|string',
            'tipo' => 'required|string',
            'foto' => 'nullable|image',
        ]);
    
        // Buscar el empleado por el ID proporcionado
        $empleado = Empleado::findOrFail($validatedData['id']);
    
        // Manejar la carga del archivo si hay una nueva foto presente en la solicitud
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($empleado->foto) {
                // Eliminar la foto anterior del almacenamiento
                $fotoAnterior = basename($empleado->foto);
                Storage::disk('public')->delete("empleados/$fotoAnterior");
            }
    
            // Guardar la nueva foto en public/empleados
            $fileName = $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('empleados'), $fileName);
    
            // Asignar la ruta relativa al campo 'foto' validado
            $validatedData['foto'] = 'empleados/' . $fileName;
        }
    
        // Actualizar los datos del empleado
        $empleado->update([
            'nombre' => $validatedData['nombre'],
            'telefono' => $validatedData['telefono'],
            'tipo' => $validatedData['tipo'],
            'foto' => $validatedData['foto'] ?? $empleado->foto, // mantener la foto existente si no se sube una nueva
        ]);
    
        // Retornar una respuesta JSON indicando éxito
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
        // Buscar el empleado por el ID proporcionado
        $empleado = Empleado::findOrFail($id);
    
        // Eliminar la foto del empleado si existe
        if ($empleado->foto) {
            // Obtener el nombre del archivo de la foto
            $foto = basename($empleado->foto);
            
            // Eliminar la foto del almacenamiento
            Storage::disk('public')->delete("empleados/$foto");
        }
    
        // Eliminar el empleado
        $empleado->delete();
    
        // Retornar una respuesta JSON indicando éxito
        return response()->json(['message' => 'Empleado eliminado correctamente'], 200);
    }
    
}
