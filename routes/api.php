<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteAuthenticationController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\NotificacionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/cliente/register', [ClienteAuthenticationController::class, 'register']);
Route::post('/cliente/login', [ClienteAuthenticationController::class, 'login']);
Route::post('/cliente/logout', [ClienteAuthenticationController::class, 'logout']);

Route::get('/verempleados', [EmpleadoController::class, 'index']);
Route::get('/empleados/{id}', [EmpleadoController::class, 'show']);
Route::post('/empleados', [EmpleadoController::class, 'store']);
Route::post('/actualizarempleado', [EmpleadoController::class, 'update']);
Route::post('/eliminarempleados/{id}', [EmpleadoController::class, 'destroy']);

Route::get('/vercitas', [CitaController::class, 'index']);
Route::get('/citas/{id}', [CitaController::class, 'show']);
Route::post('/citas', [CitaController::class, 'store']);
Route::post('/actualizarcita/{id}', [CitaController::class, 'update']);
Route::delete('/citas/{id}', [CitaController::class, 'destroy']);
Route::get('/citas/codigo/{codigo}', [CitaController::class, 'buscarPorCodigo']);

Route::get('/verservicios', [ServicioController::class, 'index']);
Route::get('/servicios/{id}', [ServicioController::class, 'show']);
Route::post('/servicios', [ServicioController::class, 'store']);
Route::post('/servicioactualizar', [ServicioController::class, 'update']);
Route::post('/servicioeliminar/{id}', [ServicioController::class, 'destroy']);

Route::get('/notificaciones', [NotificacionController::class, 'index']);
Route::get('/notificaciones/{id}', [NotificacionController::class, 'show']);
Route::post('/notificaciones/{id}', [NotificacionController::class, 'update']);
Route::delete('/notificaciones/{id}', [NotificacionController::class, 'destroy']);
Route::get('/notificacionesleida', [NotificacionController::class, 'leidas']);
Route::get('/notificacionesnoleida', [NotificacionController::class, 'noLeidas']);
