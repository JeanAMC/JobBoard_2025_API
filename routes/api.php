<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\controllerEjemplo;
use App\Http\Controllers\VacanteTrabajoController;
use App\Http\Controllers\PostulacionController;

Route::get('/ejemplo', [controllerEjemplo::class, 'miMetodo']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
  
    Route::post('/PublicarVacante', [VacanteTrabajoController::class, 'store']);
    Route::get('/Vacantes', [VacanteTrabajoController::class, 'show']);
    Route::get('/Vacantes/buscar', [VacanteTrabajoController::class, 'buscarPorTitulo']);

    Route::post('/postulaciones/{id}/cambiar-estado', [PostulacionController::class, 'cambiarEstado']);
    Route::get('/obtenerpostulaciones', [PostulacionController::class, 'index']);
    Route::post('/postulaciones', [PostulacionController::class, 'store']);
    Route::post('/postulaciones/{id}/status', [PostulacionController::class, 'updateStatus']);
  
});

