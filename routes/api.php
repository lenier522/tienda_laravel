<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/usuario',UsuarioController::class);
Route::apiResource('/categoria',CategoriaController::class);
Route::apiResource('/producto',ProductoController::class);
Route::apiResource('/compra',CompraController::class);

Route::post('/login', [UsuarioController::class, 'login']);

