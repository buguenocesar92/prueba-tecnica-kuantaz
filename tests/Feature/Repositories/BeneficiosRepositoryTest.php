<?php

namespace Tests\Feature\Repositories;

use App\DTOs\BeneficioDTO;
use App\Repositories\External\BeneficiosRepository;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BeneficiosRepositoryTest extends TestCase
{
    private BeneficiosRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new BeneficiosRepository();
    }

    public function test_constructor_sets_default_values(): void
    {
        // Verificar que el constructor se ejecuta sin errores
        $repository = new BeneficiosRepository();
        $this->assertInstanceOf(BeneficiosRepository::class, $repository);
    }

    public function test_get_all_handles_http_error_status(): void
    {
        Http::fake([
            '*' => Http::response([], 404)
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error al obtener beneficios: HTTP 404');

        $this->repository->getAll();
    }

    public function test_get_all_handles_invalid_response_format_missing_data(): void
    {
        Http::fake([
            '*' => Http::response(['invalid' => 'format'])
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Formato de respuesta inválido para beneficios');

        $this->repository->getAll();
    }

    public function test_get_all_handles_invalid_response_format_data_not_array(): void
    {
        Http::fake([
            '*' => Http::response(['data' => 'not_an_array'])
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Formato de respuesta inválido para beneficios');

        $this->repository->getAll();
    }

    public function test_get_all_handles_http_timeout(): void
    {
        Http::fake([
            '*' => function () {
                throw new \Exception('Connection timeout');
            }
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error al obtener beneficios: Connection timeout');

        $this->repository->getAll();
    }

    public function test_get_all_returns_beneficio_dtos_successfully(): void
    {
        $mockData = [
            'data' => [
                [
                    'id_programa' => 147,
                    'monto' => 40656,
                    'fecha_recepcion' => '09/11/2023',
                    'fecha' => '2023-11-09',
                    'ano' => '2023',
                    'view' => true
                ]
            ]
        ];

        Http::fake([
            '*' => Http::response($mockData)
        ]);

        $result = $this->repository->getAll();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(BeneficioDTO::class, $result[0]);
        $this->assertEquals(147, $result[0]->id_programa);
        $this->assertEquals(40656, $result[0]->monto);
    }
} 