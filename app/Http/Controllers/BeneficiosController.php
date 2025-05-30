<?php

namespace App\Http\Controllers;

use App\Services\BeneficiosService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="API de Beneficios",
 *     version="1.0.0",
 *     description="API para gestionar beneficios procesados con filtros y fichas"
 * )
 */
class BeneficiosController extends Controller
{
    public function __construct(
        private readonly BeneficiosService $beneficiosService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/beneficios-procesados",
     *     summary="Obtener beneficios procesados",
     *     description="Retorna los beneficios agrupados por año con filtros aplicados",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de beneficios procesados exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="ano", type="string", example="2024"),
     *                 @OA\Property(property="total_monto", type="integer", example=150000),
     *                 @OA\Property(property="num", type="integer", example=3),
     *                 @OA\Property(
     *                     property="beneficios",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id_programa", type="integer", example=123),
     *                         @OA\Property(property="monto", type="integer", example=50000),
     *                         @OA\Property(property="fecha_recepcion", type="string", example="2024-01-15"),
     *                         @OA\Property(property="fecha", type="string", example="2024-01-20"),
     *                         @OA\Property(property="ano", type="string", example="2024"),
     *                         @OA\Property(property="view", type="boolean", example=true),
     *                         @OA\Property(
     *                             property="ficha",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="nombre", type="string", example="Ficha Ejemplo"),
     *                             @OA\Property(property="id_programa", type="integer", example=123),
     *                             @OA\Property(property="url", type="string", example="https://ejemplo.com"),
     *                             @OA\Property(property="categoria", type="string", example="Categoría A"),
     *                             @OA\Property(property="descripcion", type="string", example="Descripción de la ficha")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al procesar beneficios")
     *         )
     *     )
     * )
     */
    public function beneficiosProcesados(): JsonResponse
    {
        try {
            $beneficiosProcesados = $this->beneficiosService->procesarBeneficios();
            
            return response()->json($beneficiosProcesados);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar beneficios: ' . $e->getMessage()
            ], 500);
        }
    }
}