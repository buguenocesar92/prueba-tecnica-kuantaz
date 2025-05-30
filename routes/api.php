<?php

use App\Http\Controllers\BeneficiosController;
use Illuminate\Support\Facades\Route;


// Rutas para la prueba técnica Kuantaz
Route::prefix('v1')->group(function () {
    Route::get('/beneficios-procesados', [BeneficiosController::class, 'beneficiosProcesados']);
});