<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Categoria::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        $categoria = new Categoria;
        $categoria -> nombre = $request -> nombre;
        $categoria -> descripcion = $request -> descripcion;

        $categoria->save();
        return $categoria;

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);
        if(is_null($categoria)){
            return response() ->json('No encontrado',404);
        }
        
        return $categoria;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        if(is_null($categoria)){
            return response() ->json('No encontrado',404);
        }

        $categoria -> nombre = $request -> nombre;
        $categoria -> descripcion = $request -> descripcion;
        

        $categoria -> update();
        return $categoria;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        if(is_null($categoria)){
            return response() ->json('No encontrado',404);
        }

        $categoria -> delete();

        return response() -> noContent();
    }
}
