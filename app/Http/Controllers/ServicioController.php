<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    /**
     * Mostrar todos los servicios
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servicios = Servicio::all();
        return response()->json($servicios);
    }

    /**
     * Mostrar un servicio específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);
        return response()->json($servicio);
    }

    /**
     * Crear un nuevo servicio
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
        ]);

        // Guardar las fotos si existen
        $fotos = [];
        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($request->hasFile($fotoField)) {
                $fotos[$fotoField] = $request->file($fotoField)->store('servicios', 'public');
            }
        }

        $servicio = Servicio::create(array_merge(
            $validatedData,
            $fotos
        ));

        return response()->json($servicio, 201);
    }

    /**
     * Actualizar un servicio
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
        ]);

        $servicio = Servicio::findOrFail($id);

        // Eliminar las fotos anteriores si existen
        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($request->hasFile($fotoField) && $servicio->$fotoField) {
                Storage::disk('public')->delete($servicio->$fotoField);
            }
        }

        // Guardar las fotos nuevas si existen
        $fotos = [];
        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($request->hasFile($fotoField)) {
                $fotos[$fotoField] = $request->file($fotoField)->store('servicios', 'public');
            }
        }

        $servicio->update(array_merge(
            $validatedData,
            $fotos
        ));

        return response()->json($servicio, 200);
    }

    /**
     * Eliminar un servicio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        // Eliminar las fotos del servicio si existen
        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($servicio->$fotoField) {
                Storage::disk('public')->delete($servicio->$fotoField);
            }
        }

        $servicio->delete();

        return response()->json(null, 204);
    }
}
