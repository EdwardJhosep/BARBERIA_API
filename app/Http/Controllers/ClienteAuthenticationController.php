<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClienteAuthenticationController extends Controller
{
    /**
     * Registro de clientes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string',
            'telefono' => 'required|string',
            'email' => 'required|email|unique:clientes,email',
            'password' => 'required|string|min:6',
            'genero' => 'required|string',
            'foto' => 'nullable|image',
        ]);

        if ($request->hasFile('foto')) {
            // Guardar la imagen directamente en public/clientes
            $path = $request->file('foto')->move(public_path('clientes'), $request->file('foto')->getClientOriginalName());
            $validatedData['foto'] = 'clientes/' . $request->file('foto')->getClientOriginalName();
        }

        // Hash de la contraseña antes de guardarla
        $validatedData['password'] = Hash::make($validatedData['password']);

        $cliente = Cliente::create($validatedData);

        return response()->json([
            'message' => 'Registro exitoso',
            'cliente' => $cliente
        ], 201);
    }

    /**
     * Inicio de sesión de clientes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $cliente = Cliente::where('email', $credentials['email'])->first();

        if (!$cliente || !Hash::check($credentials['password'], $cliente->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return response()->json(['message' => 'Sesión iniciada correctamente', 'cliente' => $cliente]);
    }
    
    /**
     * Cierre de sesión de clientes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
