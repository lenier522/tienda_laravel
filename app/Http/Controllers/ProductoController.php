<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Producto::with('categoria')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          // Validación de los datos enviados en la petición
          $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validar imagen opcional
            'categoria_id' => 'required|exists:categorias,id', // Asegurar que la categoría exista
        ]);

        // Crear una nueva instancia de Producto
        $producto = new Producto();
        $producto->titulo = $request->titulo;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->cantidad = $request->cantidad;
        $producto->categoria_id = $request->categoria_id;

        // Manejo de la imagen (si se ha enviado una)
        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $imagePath;
        }

        // Guardar el producto en la base de datos
        $producto->save();

        // Retornar una respuesta exitosa
        return response()->json([
            'message' => 'Producto publicado con éxito.',
            'producto' => $producto
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        if(is_null($producto)){
            return response() ->json('No encontrado',404);
        }
        
        return $producto;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $producto = Producto::find($id);
        if (is_null($producto)) {
            return response() ->json('No encontrado',404);
        }

        $producto->titulo = $request->titulo;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->cantidad = $request->cantidad;
        $producto->categoria_id = $request->categoria_id;


        $producto -> update();
        return $producto;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (is_null($producto)) {
            return response() ->json('No encontrado',404);
        }

        $producto -> delete();

        return response() -> noContent();
    }
}
