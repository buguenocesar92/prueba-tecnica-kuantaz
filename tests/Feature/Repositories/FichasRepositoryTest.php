<?php

namespace Tests\Feature\Repositories;

use App\DTOs\FichaDTO;
use App\Repositories\External\FichasRepository;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FichasRepositoryTest extends TestCase
{
    private FichasRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new FichasRepository();
    }

    public function test_constructor_sets_default_values(): void
    {
        // Verificar que el constructor se ejecuta sin errores
        $repository = new FichasRepository();
        $this->assertInstanceOf(FichasRepository::class, $repository);
    }

    public function test_get_all_handles_http_error_status(): void
    {
        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error al obtener fichas: HTTP 500');

        $this->repository->getAll();
    }

    public function test_get_all_handles_invalid_response_format_missing_data(): void
    {
        Http::fake([
            '*' => Http::response(['invalid' => 'format'])
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Formato de respuesta inválido para fichas');

        $this->repository->getAll();
    }

    public function test_get_all_handles_invalid_response_format_data_not_array(): void
    {
        Http::fake([
            '*' => Http::response(['data' => 'not_an_array'])
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Formato de respuesta inválido para fichas');

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
        $this->expectExceptionMessage('Error al obtener fichas: Connection timeout');

        $this->repository->getAll();
    }

    public function test_get_all_returns_ficha_dtos_successfully(): void
    {
        $mockData = [
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
        ];

        Http::fake([
            '*' => Http::response($mockData)
        ]);

        $result = $this->repository->getAll();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(FichaDTO::class, $result[0]);
        $this->assertEquals(922, $result[0]->id);
        $this->assertEquals('Emprende', $result[0]->nombre);
        $this->assertEquals(147, $result[0]->id_programa);
    }
} 