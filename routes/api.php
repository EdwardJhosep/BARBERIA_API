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
Route::put('/empleados/{id}', [EmpleadoController::class, 'update']);
Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy']);

Route::get('/vercitas', [CitaController::class, 'index']);
Route::get('/citas/{id}', [CitaController::class, 'show']);
Route::post('/citas', [CitaController::class, 'store']);
Route::put('/citas/{id}', [CitaController::class, 'update']);
Route::delete('/citas/{id}', [CitaController::class, 'destroy']);
Route::get('/citas/codigo/{codigo}', [CitaController::class, 'buscarPorCodigo']);

Route::get('/verservicios', [ServicioController::class, 'index']);
Route::get('/servicios/{id}', [ServicioController::class, 'show']);
Route::post('/servicios', [ServicioController::class, 'store']);
Route::put('/servicios/{id}', [ServicioController::class, 'update']);
Route::delete('/servicios/{id}', [ServicioController::class, 'destroy']);

Route::get('/notificaciones', [NotificacionController::class, 'index']);
Route::get('/notificaciones/{id}', [NotificacionController::class, 'show']);
Route::post('/notificaciones', [NotificacionController::class, 'store']);
Route::put('/notificaciones/{id}', [NotificacionController::class, 'update']);
Route::delete('/notificaciones/{id}', [NotificacionController::class, 'destroy']);
