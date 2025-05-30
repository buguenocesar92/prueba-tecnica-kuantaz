<?php

namespace Tests\Feature\Repositories;

use App\DTOs\FiltroDTO;
use App\Repositories\External\FiltrosRepository;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FiltrosRepositoryTest extends TestCase
{
    private FiltrosRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new FiltrosRepository();
    }

    public function test_constructor_sets_default_values(): void
    {
        // Verificar que el constructor se ejecuta sin errores
        $repository = new FiltrosRepository();
        $this->assertInstanceOf(FiltrosRepository::class, $repository);
    }

    public function test_get_all_handles_http_error_status(): void
    {
        Http::fake([
            '*' => Http::response([], 403)
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error al obtener filtros: HTTP 403');

        $this->repository->getAll();
    }

    public function test_get_all_handles_invalid_response_format_missing_data(): void
    {
        Http::fake([
            '*' => Http::response(['invalid' => 'format'])
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Formato de respuesta inválido para filtros');

        $this->repository->getAll();
    }

    public function test_get_all_handles_invalid_response_format_data_not_array(): void
    {
        Http::fake([
            '*' => Http::response(['data' => 'not_an_array'])
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Formato de respuesta inválido para filtros');

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
        $this->expectExceptionMessage('Error al obtener filtros: Connection timeout');

        $this->repository->getAll();
    }

    public function test_get_all_returns_filtro_dtos_successfully(): void
    {
        $mockData = [
            'data' => [
                [
                    'id_programa' => 147,
                    'tramite' => 'Emprende',
                    'min' => 10000,
                    'max' => 50000,
                    'ficha_id' => 922
                ]
            ]
        ];

        Http::fake([
            '*' => Http::response($mockData)
        ]);

        $result = $this->repository->getAll();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(FiltroDTO::class, $result[0]);
        $this->assertEquals(147, $result[0]->id_programa);
        $this->assertEquals('Emprende', $result[0]->tramite);
        $this->assertEquals(10000, $result[0]->min);
        $this->assertEquals(50000, $result[0]->max);
    }
} 