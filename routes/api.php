<?php

use App\Http\Controllers\BeneficiosController;
use Illuminate\Support\Facades\Route;


// Rutas para la prueba técnica Kuantaz
Route::prefix('v1')->group(function () {
    Route::get('/beneficios-procesados', [BeneficiosController::class, 'getBeneficiosProcesados']);
    
    // Endpoints auxiliares para testing
    Route::get('/beneficios', [BeneficiosController::class, 'getBeneficios']);
    Route::get('/filtros', [BeneficiosController::class, 'getFiltros']);
    Route::get('/fichas', [BeneficiosController::class, 'getFichas']);
});