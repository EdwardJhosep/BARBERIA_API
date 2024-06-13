<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
            $validatedData['foto'] = $request->file('foto')->store('clientes', 'public');
        }

        $cliente = Cliente::create(array_merge(
            $validatedData,
            ['password' => Hash::make($request->password)]
        ));

        return response()->json($cliente, 201);
    }

    /**
     * Inicio de sesión de clientes sin autenticación adicional
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

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $cliente = Cliente::where('email', $credentials['email'])->firstOrFail();

        return response()->json($cliente, 200);
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

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
