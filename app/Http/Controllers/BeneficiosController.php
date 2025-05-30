<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
/**
 * @OA\Info(
 *     title="Kuantaz API",
 *     version="1.0.0",
 *     description="API para gestión de beneficios"
 * )
 */
class BeneficiosController extends Controller
{
    private $beneficiosUrl;
    private $filtrosUrl;
    private $fichasUrl;

    public function __construct()
    {
        $this->beneficiosUrl = env('BENEFICIOS_API_URL', 'https://run.mocky.io/v3/8f75c4b5-ad90-49bb-bc52-f1fc0b4aad02');
        $this->filtrosUrl = env('FILTROS_API_URL', 'https://run.mocky.io/v3/b0ddc735-cfc9-410e-9365-137e04e33fcf');
        $this->fichasUrl = env('FICHAS_API_URL', 'https://run.mocky.io/v3/4654cafa-58d8-4846-9256-79841b29a687');
    }

    /**
     * @OA\Get(
     *     path="/api/beneficios-procesados",
     *     summary="Obtener beneficios procesados y agrupados por año",
     *     description="Retorna los beneficios ordenados por año (mayor a menor), con número de beneficios, filtrados por montos mín/máx y con información de fichas",
     *     @OA\Response(
     *         response=200,
     *         description="Beneficios procesados exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="year", type="integer", example=2023),
     *                     @OA\Property(property="total_monto", type="integer", example=250000),
     *                     @OA\Property(property="num", type="integer", example=8),
     *                     @OA\Property(
     *                         property="beneficios",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id_programa", type="integer"),
     *                             @OA\Property(property="monto", type="integer"),
     *                             @OA\Property(property="fecha_recepcion", type="string"),
     *                             @OA\Property(property="fecha", type="string"),
     *                             @OA\Property(property="ano", type="string"),
     *                             @OA\Property(property="view", type="boolean"),
     *                             @OA\Property(property="ficha", type="object")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function getBeneficiosProcesados()
    {
        try {
            // Obtener datos de los 3 endpoints
            $beneficios = $this->fetchBeneficios();
            $filtros = $this->fetchFiltros();
            $fichas = $this->fetchFichas();

            if ($beneficios === null || $filtros === null || $fichas === null) {
                return response()->json([
                    'code' => 500,
                    'success' => false,
                    'message' => 'Error al obtener datos de los endpoints'
                ], 500);
            }

            // Procesar los datos (puede devolver array vacío si no hay datos válidos)
            $beneficiosProcesados = $this->procesarBeneficios($beneficios, $filtros, $fichas);

            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $beneficiosProcesados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }

    private function fetchBeneficios()
    {
        try {
            $response = Http::timeout(30)->get($this->beneficiosUrl);
            return $response->successful() ? $response->json()['data'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function fetchFiltros()
    {
        try {
            $response = Http::timeout(30)->get($this->filtrosUrl);
            return $response->successful() ? $response->json()['data'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function fetchFichas()
    {
        try {
            $response = Http::timeout(30)->get($this->fichasUrl);
            return $response->successful() ? $response->json()['data'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function procesarBeneficios($beneficios, $filtros, $fichas)
    {
        // Convertir a colecciones de Laravel
        $beneficiosCollection = collect($beneficios);
        $filtrosCollection = collect($filtros);
        $fichasCollection = collect($fichas);

        // Crear mapas para búsqueda rápida
        $filtrosMap = $filtrosCollection->keyBy('id_programa');
        $fichasMap = $fichasCollection->keyBy('id');

        // Filtrar beneficios que cumplan con montos mín/máx y agregar información
        $beneficiosFiltrados = $beneficiosCollection
            ->filter(function ($beneficio) use ($filtrosMap) {
                $filtro = $filtrosMap->get($beneficio['id_programa']);
                if (!$filtro) return false;
                
                return $beneficio['monto'] >= $filtro['min'] && 
                       $beneficio['monto'] <= $filtro['max'];
            })
            ->map(function ($beneficio) use ($filtrosMap, $fichasMap) {
                $filtro = $filtrosMap->get($beneficio['id_programa']);
                $ficha = $fichasMap->get($filtro['ficha_id']);
                
                return array_merge($beneficio, [
                    'ano' => date('Y', strtotime($beneficio['fecha'])),
                    'view' => true,
                    'ficha' => $ficha
                ]);
            });

        // Agrupar por año y procesar
        $beneficiosPorAno = $beneficiosFiltrados
            ->groupBy('ano')
            ->map(function ($beneficios, $ano) {
                $beneficiosOrdenados = $beneficios->sortByDesc('fecha')->values();
                
                return [
                    'year' => (int) $ano,
                    'total_monto' => $beneficios->sum('monto'),
                    'num' => $beneficios->count(),
                    'beneficios' => $beneficiosOrdenados->toArray()
                ];
            })
            ->sortByDesc('year')
            ->values()
            ->toArray();

        return $beneficiosPorAno;
    }

    /**
     * Endpoint auxiliar para obtener beneficios raw (para testing)
     */
    public function getBeneficios()
    {
        $beneficios = $this->fetchBeneficios();
        
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $beneficios
        ]);
    }

    /**
     * Endpoint auxiliar para obtener filtros raw (para testing)
     */
    public function getFiltros()
    {
        $filtros = $this->fetchFiltros();
        
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $filtros
        ]);
    }

    /**
     * Endpoint auxiliar para obtener fichas raw (para testing)
     */
    public function getFichas()
    {
        $fichas = $this->fetchFichas();
        
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $fichas
        ]);
    }
}