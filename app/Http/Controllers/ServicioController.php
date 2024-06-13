<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'descuento' => 'nullable|numeric',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
        ]);

        $fotos = [];
        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($request->hasFile($fotoField)) {
                $foto = $request->file($fotoField);
                $fileName = Str::random(20) . '.' . $foto->getClientOriginalExtension();
                $path = $foto->move(public_path('servicios'), $fileName); // Almacenamos en public/servicios
                $fotos[$fotoField] = '/servicios/' . $fileName; // Guardamos la ruta completa
            }
        }

        $precio_final = $validatedData['precio'];
        if (isset($validatedData['descuento'])) {
            $precio_final -= $validatedData['descuento'];
        }

        $servicio = Servicio::create(array_merge(
            $validatedData,
            $fotos,
            ['precio_final' => $precio_final]
        ));

        return response()->json($servicio, 201);
    }

    /**
     * Actualizar un servicio existente
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:servicios,id',
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'descuento' => 'nullable|numeric',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
        ]);

        $servicio = Servicio::findOrFail($request->id);

        // Eliminar fotos antiguas si se envían nuevas fotos
        $fotos = [];
        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($request->hasFile($fotoField)) {
                // Eliminar la foto antigua si existe
                if ($servicio->{$fotoField}) {
                    $this->deleteServicioFoto($servicio->{$fotoField});
                }

                $foto = $request->file($fotoField);
                $fileName = Str::random(20) . '.' . $foto->getClientOriginalExtension();
                $path = $foto->move(public_path('servicios'), $fileName);
                $fotos[$fotoField] = '/servicios/' . $fileName;
            }
        }

        $precio_final = $validatedData['precio'];
        if (isset($validatedData['descuento'])) {
            $precio_final -= $validatedData['descuento'];
        }

        // Actualizar los datos del servicio
        $servicio->update(array_merge(
            $validatedData,
            $fotos,
            ['precio_final' => $precio_final]
        ));

        return response()->json($servicio, 200);
    }

    /**
     * Eliminar un servicio existente
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        // Eliminar las fotos si existen
        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($servicio->{$fotoField}) {
                $this->deleteServicioFoto($servicio->{$fotoField});
            }
        }

        // Eliminar el servicio
        $servicio->delete();

        return response()->json(['message' => 'Servicio eliminado correctamente'], 200);
    }

    /**
     * Eliminar la foto de un servicio
     *
     * @param  string  $photoPath
     * @return void
     */
    protected function deleteServicioFoto($photoPath)
    {
        $photoPath = public_path($photoPath);

        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }
}
