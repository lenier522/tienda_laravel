<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Compra::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        // Crear la compra
        $compra = Compra::create([
            'usuario_id' => $request->usuario_id,
            'total' => 0 // Inicializamos en 0 y luego lo calculamos
        ]);

        $total = 0;

        // Agregar los productos a la compra y actualizar la cantidad del inventario
        foreach ($request->productos as $productoData) {
            $producto = Producto::find($productoData['id']);
            $cantidad = $productoData['cantidad'];
            $precio = $producto->precio;

            // Verificar si hay suficiente stock disponible
            if ($producto->cantidad < $cantidad) {
                return response()->json([
                    'message' => 'No hay suficiente stock para el producto: ' . $producto->titulo
                ], 400); // Error 400 si no hay suficiente stock
            }

            // Agregar a la tabla intermedia
            $compra->productos()->attach($producto->id, [
                'cantidad' => $cantidad,
                'precio' => $precio
            ]);

            // Calcular el total
            $total += $precio * $cantidad;

            // Reducir la cantidad disponible en el inventario
            $producto->cantidad -= $cantidad;
            $producto->save(); // Guardar los cambios en la cantidad del producto
        }

        // Actualizar el total de la compra
        $compra->update(['total' => $total]);

        return response()->json([
            'message' => 'Compra realizada con éxito',
            'compra' => $compra->load('productos')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $compra = Compra::find($id);
        if(is_null($compra)){
            return response() ->json('No encontrado',404);
        }
        
        return $compra;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $compra = Compra::find($id);
        if(is_null($compra)){
            return response() ->json('No encontrado',404);
        }

        $compra -> cantidad = $request -> cantidad;
        $compra -> precio = $request -> precio;
        

        $compra -> update();
        return $compra;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $compra = Compra::find($id);
        if(is_null($compra)){
            return response() ->json('No encontrado',404);
        }

        $compra -> delete();

        return response() -> noContent();
    }
}
