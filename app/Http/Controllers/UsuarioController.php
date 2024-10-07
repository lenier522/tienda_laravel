<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Usuario::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'direccion' => 'required',
            'telefono' => 'required|numeric',
            'password' => 'required',
        ]);

        $usuario = new Usuario;
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->direccion = $request->direccion;
        $usuario->telefono = $request->telefono;
        $usuario->password = bcrypt($request->password);

        $usuario->save();
        return response()->json($usuario, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return response()->json('No encontrado', 404);
        }

        return $usuario;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return response()->json('No encontrado', 404);
        }

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->direccion = $request->direccion;
        $usuario->telefono = $request->telefono;
        $usuario->password = bcrypt($request->password);

        $usuario->update();
        return $usuario;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return response()->json('No encontrado', 404);
        }

        $usuario->delete();

        return response()->noContent();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $usuario = Usuario::where('email', $request->email)->first();
    
        // Verificamos la contraseña usando password_verify
        if (!$usuario || !password_verify($request->password, $usuario->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    
        return response()->json($usuario);
    }
    
}
