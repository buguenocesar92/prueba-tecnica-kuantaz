<?php

namespace App\Http\Controllers;

use App\Services\BeneficiosService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="API de Beneficios - Kuantaz",
 *     version="1.0.0",
 *     description="API para gestionar beneficios procesados con filtros y fichas. Esta API consume datos de fuentes externas, aplica filtros de montos mínimos y máximos, y agrupa los beneficios por año con sus respectivas fichas asociadas.",
 *
 *     @OA\Contact(
 *         email="buguenocesar92@gmail.com",
 *         name="César Bugueno"
 *     ),
 *
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Servidor de Desarrollo Local"
 * )
 *
 * @OA\Tag(
 *     name="Beneficios",
 *     description="Endpoints para gestión de beneficios procesados"
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
     *     operationId="getBeneficiosProcesados",
     *     tags={"Beneficios"},
     *     summary="Obtener beneficios procesados agrupados por año",
     *     description="Retorna los beneficios agrupados por año con filtros aplicados por montos mínimos y máximos. Cada beneficio incluye su ficha asociada con información detallada del programa.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de beneficios procesados exitosamente",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *
     *                 @OA\Property(property="ano", type="string", example="2023", description="Año de los beneficios"),
     *                 @OA\Property(property="total_monto", type="integer", example=295608, description="Suma total de montos para el año"),
     *                 @OA\Property(property="num", type="integer", example=8, description="Número total de beneficios para el año"),
     *                 @OA\Property(
     *                     property="beneficios",
     *                     type="array",
     *                     description="Lista de beneficios del año ordenados por fecha",
     *
     *                     @OA\Items(
     *                         type="object",
     *
     *                         @OA\Property(property="id_programa", type="integer", example=147, description="ID del programa de beneficio"),
     *                         @OA\Property(property="monto", type="integer", example=40656, description="Monto del beneficio"),
     *                         @OA\Property(property="fecha_recepcion", type="string", example="09/11/2023", description="Fecha de recepción del beneficio"),
     *                         @OA\Property(property="fecha", type="string", example="2023-11-09", description="Fecha del beneficio en formato ISO"),
     *                         @OA\Property(property="ano", type="string", example="2023", description="Año extraído de la fecha"),
     *                         @OA\Property(property="view", type="boolean", example=true, description="Indicador de visibilidad"),
     *                         @OA\Property(
     *                             property="ficha",
     *                             type="object",
     *                             description="Información detallada del programa",
     *                             @OA\Property(property="id", type="integer", example=922, description="ID único de la ficha"),
     *                             @OA\Property(property="nombre", type="string", example="Emprende", description="Nombre del programa"),
     *                             @OA\Property(property="id_programa", type="integer", example=147, description="ID del programa asociado"),
     *                             @OA\Property(property="url", type="string", example="emprende", description="URL slug del programa"),
     *                             @OA\Property(property="categoria", type="string", example="trabajo", description="Categoría del programa"),
     *                             @OA\Property(property="descripcion", type="string", example="Fondos concursables para nuevos negocios", description="Descripción detallada del programa")
     *                         )
     *                     )
     *                 )
     *             ),
     *             example={
     *                 {
     *                     "ano": "2023",
     *                     "total_monto": 295608,
     *                     "num": 8,
     *                     "beneficios": {
     *                         {
     *                             "id_programa": 147,
     *                             "monto": 40656,
     *                             "fecha_recepcion": "09/11/2023",
     *                             "fecha": "2023-11-09",
     *                             "ano": "2023",
     *                             "view": true,
     *                             "ficha": {
     *                                 "id": 922,
     *                                 "nombre": "Emprende",
     *                                 "id_programa": 147,
     *                                 "url": "emprende",
     *                                 "categoria": "trabajo",
     *                                 "descripcion": "Fondos concursables para nuevos negocios"
     *                             }
     *                         }
     *                     }
     *                 },
     *                 {
     *                     "ano": "2022",
     *                     "total_monto": 150000,
     *                     "num": 3,
     *                     "beneficios": {}
     *                 }
     *             }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Error al procesar beneficios: Error al obtener beneficios desde API externa"),
     *             example={
     *                 "error": "Error al procesar beneficios: Error al obtener beneficios desde API externa"
     *             }
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
                'error' => 'Error al procesar beneficios: '.$e->getMessage(),
            ], 500);
        }
    }
}
