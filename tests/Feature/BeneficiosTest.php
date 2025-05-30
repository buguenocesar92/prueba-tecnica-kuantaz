<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BeneficiosTest extends TestCase
{
    private function getBeneficiosUrl()
    {
        return env('BENEFICIOS_API_URL', 'https://run.mocky.io/v3/8f75c4b5-ad90-49bb-bc52-f1fc0b4aad02');
    }

    private function getFiltrosUrl()
    {
        return env('FILTROS_API_URL', 'https://run.mocky.io/v3/b0ddc735-cfc9-410e-9365-137e04e33fcf');
    }

    private function getFichasUrl()
    {
        return env('FICHAS_API_URL', 'https://run.mocky.io/v3/4654cafa-58d8-4846-9256-79841b29a687');
    }

    public function test_beneficios_procesados_endpoint_returns_correct_structure()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147,
                        'monto' => 40656,
                        'fecha_recepcion' => '09/11/2023',
                        'fecha' => '2023-11-09'
                    ]
                ]
            ], 200),
            
            $this->getFiltrosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147,
                        'tramite' => 'Emprende',
                        'min' => 0,
                        'max' => 50000,
                        'ficha_id' => 922
                    ]
                ]
            ], 200),
            
            $this->getFichasUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id' => 922,
                        'nombre' => 'Emprende',
                        'id_programa' => 147,
                        'url' => 'emprende',
                        'categoria' => 'trabajo',
                        'descripcion' => 'Fondos concursables para nuevos negocios'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'ano',
                        'total_monto',
                        'num',
                        'beneficios' => [
                            '*' => [
                                'id_programa',
                                'monto',
                                'fecha_recepcion',
                                'fecha',
                                'ano',
                                'view',
                                'ficha' => [
                                    'id',
                                    'nombre',
                                    'id_programa',
                                    'url',
                                    'categoria',
                                    'descripcion'
                                ]
                            ]
                        ]
                    ]
                ]);
    }

    public function test_beneficios_procesados_filters_by_min_max_amounts()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 130,
                        'monto' => 1000, // Este debe ser filtrado (menor al mínimo)
                        'fecha_recepcion' => '09/05/2023',
                        'fecha' => '2023-05-09'
                    ],
                    [
                        'id_programa' => 130,
                        'monto' => 10000, // Este debe pasar el filtro
                        'fecha_recepcion' => '08/06/2023',
                        'fecha' => '2023-06-08'
                    ]
                ]
            ], 200),
            
            $this->getFiltrosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 130,
                        'tramite' => 'Subsidio Único Familiar',
                        'min' => 5000,
                        'max' => 180000,
                        'ficha_id' => 2042
                    ]
                ]
            ], 200),
            
            $this->getFichasUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id' => 2042,
                        'nombre' => 'Subsidio Familiar (SUF)',
                        'id_programa' => 130,
                        'url' => 'subsidio_familiar_suf',
                        'categoria' => 'bonos',
                        'descripcion' => 'Beneficio económico mensual'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(200);
        
        $data = $response->json();
        
        // Verificar que solo hay un beneficio (el de 10000, ya que 1000 fue filtrado)
        $this->assertEquals(1, $data[0]['num']);
        $this->assertEquals(10000, $data[0]['beneficios'][0]['monto']);
    }

    public function test_beneficios_procesados_orders_by_year_desc()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147,
                        'monto' => 40656,
                        'fecha_recepcion' => '09/11/2022',
                        'fecha' => '2022-11-09'
                    ],
                    [
                        'id_programa' => 147,
                        'monto' => 40656,
                        'fecha_recepcion' => '09/11/2023',
                        'fecha' => '2023-11-09'
                    ]
                ]
            ], 200),
            
            $this->getFiltrosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147,
                        'tramite' => 'Emprende',
                        'min' => 0,
                        'max' => 50000,
                        'ficha_id' => 922
                    ]
                ]
            ], 200),
            
            $this->getFichasUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id' => 922,
                        'nombre' => 'Emprende',
                        'id_programa' => 147,
                        'url' => 'emprende',
                        'categoria' => 'trabajo',
                        'descripcion' => 'Fondos concursables para nuevos negocios'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(200);
        
        $data = $response->json();
        
        // Verificar que 2023 viene antes que 2022 (orden descendente)
        $this->assertEquals('2023', $data[0]['ano']);
        $this->assertEquals('2022', $data[1]['ano']);
    }

    public function test_beneficios_procesados_calculates_total_amount_correctly()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147,
                        'monto' => 20000,
                        'fecha_recepcion' => '09/11/2023',
                        'fecha' => '2023-11-09'
                    ],
                    [
                        'id_programa' => 147,
                        'monto' => 30000,
                        'fecha_recepcion' => '10/11/2023',
                        'fecha' => '2023-11-10'
                    ]
                ]
            ], 200),
            
            $this->getFiltrosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147,
                        'tramite' => 'Emprende',
                        'min' => 0,
                        'max' => 50000,
                        'ficha_id' => 922
                    ]
                ]
            ], 200),
            
            $this->getFichasUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id' => 922,
                        'nombre' => 'Emprende',
                        'id_programa' => 147,
                        'url' => 'emprende',
                        'categoria' => 'trabajo',
                        'descripcion' => 'Fondos concursables para nuevos negocios'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(200);
        
        $data = $response->json();
        
        // Verificar que el total es 50000 (20000 + 30000)
        $this->assertEquals(50000, $data[0]['total_monto']);
        $this->assertEquals(2, $data[0]['num']);
    }

    public function test_external_api_failure_returns_error()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([], 500)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(500)
                ->assertJson([
                    'error' => 'Error al procesar beneficios: Error al obtener beneficios: Error al obtener beneficios: HTTP 500'
                ]);
    }

    public function test_beneficios_without_valid_filters_are_excluded()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 999, // Programa sin filtro
                        'monto' => 25000,
                        'fecha_recepcion' => '09/11/2023',
                        'fecha' => '2023-11-09'
                    ]
                ]
            ], 200),
            
            $this->getFiltrosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147, // Filtro para otro programa
                        'tramite' => 'Emprende',
                        'min' => 0,
                        'max' => 50000,
                        'ficha_id' => 922
                    ]
                ]
            ], 200),
            
            $this->getFichasUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id' => 922,
                        'nombre' => 'Emprende',
                        'id_programa' => 147,
                        'url' => 'emprende',
                        'categoria' => 'trabajo',
                        'descripcion' => 'Fondos concursables para nuevos negocios'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(200);
        
        $data = $response->json();
        
        // Verificar que no hay beneficios porque no hay filtros válidos
        $this->assertEmpty($data);
    }

    public function test_empty_beneficios_returns_empty_array()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => []
            ], 200),
            
            $this->getFiltrosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => []
            ], 200),
            
            $this->getFichasUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => []
            ], 200)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(200)
                ->assertJson([]);
    }

    public function test_beneficios_orders_within_year_by_date_asc()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147,
                        'monto' => 40656,
                        'fecha_recepcion' => '09/12/2023',
                        'fecha' => '2023-12-09' // Fecha más reciente
                    ],
                    [
                        'id_programa' => 147,
                        'monto' => 40656,
                        'fecha_recepcion' => '09/06/2023',
                        'fecha' => '2023-06-09' // Fecha más antigua
                    ]
                ]
            ], 200),
            
            $this->getFiltrosUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id_programa' => 147,
                        'tramite' => 'Emprende',
                        'min' => 0,
                        'max' => 50000,
                        'ficha_id' => 922
                    ]
                ]
            ], 200),
            
            $this->getFichasUrl() => Http::response([
                'code' => 200,
                'success' => true,
                'data' => [
                    [
                        'id' => 922,
                        'nombre' => 'Emprende',
                        'id_programa' => 147,
                        'url' => 'emprende',
                        'categoria' => 'trabajo',
                        'descripcion' => 'Fondos concursables para nuevos negocios'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(200);
        
        $data = $response->json();
        $beneficios = $data[0]['beneficios'];
        
        // Verificar que dentro del año están ordenados por fecha ascendente
        $this->assertEquals('2023-06-09', $beneficios[0]['fecha']);
        $this->assertEquals('2023-12-09', $beneficios[1]['fecha']);
    }

    public function test_multiple_api_failures_returns_error()
    {
        Http::fake([
            $this->getBeneficiosUrl() => Http::response([], 500),
            $this->getFiltrosUrl() => Http::response([], 500),
            $this->getFichasUrl() => Http::response([], 500)
        ]);

        $response = $this->getJson('/api/v1/beneficios-procesados');

        $response->assertStatus(500)
                ->assertJson([
                    'error' => 'Error al procesar beneficios: Error al obtener beneficios: Error al obtener beneficios: HTTP 500'
                ]);
    }
} 