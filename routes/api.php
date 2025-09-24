<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('usuario')->group(function(){
    Route::post('registrar-se', [App\Http\Controllers\UsuarioController::class, 'registrar']);
    Route::post('usuario/login', [UsuarioController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\UsuarioController::class, 'logout']);
    Route::post('desativar-conta', [App\Http\Controllers\UsuarioController::class, 'desativarConta']);
    Route::post('foto-upload', [App\Http\Controllers\UsuarioController::class, 'fotoUpload']);
    Route::post('editar', [App\Http\Controllers\UsuarioController::class, 'editar']);
    Route::get('perfil', [App\Http\Controllers\UsuarioController::class, 'perfil']);
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('postagens', [PostController::class, 'index']);
    Route::post('postagens', [PostController::class, 'store']);
});
