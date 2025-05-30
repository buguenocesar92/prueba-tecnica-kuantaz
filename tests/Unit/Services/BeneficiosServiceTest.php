<?php

namespace Tests\Unit\Services;

use App\DTOs\BeneficioDTO;
use App\DTOs\FichaDTO;
use App\DTOs\FiltroDTO;
use App\Repositories\Interfaces\BeneficiosRepositoryInterface;
use App\Repositories\Interfaces\FichasRepositoryInterface;
use App\Repositories\Interfaces\FiltrosRepositoryInterface;
use App\Services\BeneficiosService;
use PHPUnit\Framework\TestCase;

class BeneficiosServiceTest extends TestCase
{
    private BeneficiosService $service;
    private $beneficiosRepository;
    private $filtrosRepository;
    private $fichasRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear mocks de los repositorios
        $this->beneficiosRepository = $this->createMock(BeneficiosRepositoryInterface::class);
        $this->filtrosRepository = $this->createMock(FiltrosRepositoryInterface::class);
        $this->fichasRepository = $this->createMock(FichasRepositoryInterface::class);

        // Crear el servicio con los mocks
        $this->service = new BeneficiosService(
            $this->beneficiosRepository,
            $this->filtrosRepository,
            $this->fichasRepository
        );
    }

    public function test_procesar_beneficios_con_datos_validos()
    {
        // Arrange
        $beneficios = [
            new BeneficioDTO(123, 25000, '2023-01-15', '2023-01-20'),
            new BeneficioDTO(124, 15000, '2023-02-10', '2023-02-15'),
            new BeneficioDTO(125, 35000, '2024-01-10', '2024-01-15')
        ];

        $filtros = [
            new FiltroDTO(123, 'Tramite A', 20000, 30000, 1),
            new FiltroDTO(124, 'Tramite B', 10000, 20000, 2),
            new FiltroDTO(125, 'Tramite C', 30000, 40000, 3)
        ];

        $fichas = [
            new FichaDTO(1, 'Ficha A', 123, 'url1', 'Cat A', 'Desc A'),
            new FichaDTO(2, 'Ficha B', 124, 'url2', 'Cat B', 'Desc B'),
            new FichaDTO(3, 'Ficha C', 125, 'url3', 'Cat C', 'Desc C')
        ];

        // Configurar mocks
        $this->beneficiosRepository->method('getAll')->willReturn($beneficios);
        $this->filtrosRepository->method('getAll')->willReturn($filtros);
        $this->fichasRepository->method('getAll')->willReturn($fichas);

        // Act
        $resultado = $this->service->procesarBeneficios();

        // Assert
        $this->assertCount(2, $resultado); // 2023 y 2024
        
        // Verificar año 2024 (debe estar primero por orden descendente)
        $this->assertEquals('2024', $resultado[0]['ano']);
        $this->assertEquals(35000, $resultado[0]['total_monto']);
        $this->assertEquals(1, $resultado[0]['num']);
        
        // Verificar año 2023
        $this->assertEquals('2023', $resultado[1]['ano']);
        $this->assertEquals(40000, $resultado[1]['total_monto']); // 25000 + 15000
        $this->assertEquals(2, $resultado[1]['num']);
    }

    public function test_filtrar_beneficios_por_monto_minimo_maximo()
    {
        // Arrange - beneficio que no cumple el filtro de monto
        $beneficios = [
            new BeneficioDTO(123, 5000, '2023-01-15', '2023-01-20'), // Muy bajo
            new BeneficioDTO(124, 50000, '2023-02-10', '2023-02-15') // Muy alto
        ];

        $filtros = [
            new FiltroDTO(123, 'Tramite A', 10000, 30000, 1),
            new FiltroDTO(124, 'Tramite B', 10000, 30000, 2)
        ];

        $fichas = [
            new FichaDTO(1, 'Ficha A', 123, 'url1', 'Cat A', 'Desc A'),
            new FichaDTO(2, 'Ficha B', 124, 'url2', 'Cat B', 'Desc B')
        ];

        $this->beneficiosRepository->method('getAll')->willReturn($beneficios);
        $this->filtrosRepository->method('getAll')->willReturn($filtros);
        $this->fichasRepository->method('getAll')->willReturn($fichas);

        // Act
        $resultado = $this->service->procesarBeneficios();

        // Assert
        $this->assertEmpty($resultado); // Ningún beneficio cumple los filtros
    }

    public function test_beneficios_sin_filtro_son_excluidos()
    {
        // Arrange
        $beneficios = [
            new BeneficioDTO(999, 25000, '2023-01-15', '2023-01-20') // Programa sin filtro
        ];

        $filtros = [
            new FiltroDTO(123, 'Tramite A', 20000, 30000, 1) // Filtro para otro programa
        ];

        $fichas = [
            new FichaDTO(1, 'Ficha A', 123, 'url1', 'Cat A', 'Desc A')
        ];

        $this->beneficiosRepository->method('getAll')->willReturn($beneficios);
        $this->filtrosRepository->method('getAll')->willReturn($filtros);
        $this->fichasRepository->method('getAll')->willReturn($fichas);

        // Act
        $resultado = $this->service->procesarBeneficios();

        // Assert
        $this->assertEmpty($resultado);
    }

    public function test_beneficios_sin_ficha_son_excluidos()
    {
        // Arrange
        $beneficios = [
            new BeneficioDTO(123, 25000, '2023-01-15', '2023-01-20')
        ];

        $filtros = [
            new FiltroDTO(123, 'Tramite A', 20000, 30000, 999) // Ficha inexistente
        ];

        $fichas = [
            new FichaDTO(1, 'Ficha A', 123, 'url1', 'Cat A', 'Desc A') // Ficha con ID diferente
        ];

        $this->beneficiosRepository->method('getAll')->willReturn($beneficios);
        $this->filtrosRepository->method('getAll')->willReturn($filtros);
        $this->fichasRepository->method('getAll')->willReturn($fichas);

        // Act
        $resultado = $this->service->procesarBeneficios();

        // Assert
        $this->assertEmpty($resultado);
    }

    public function test_ordenamiento_por_ano_descendente()
    {
        // Arrange
        $beneficios = [
            new BeneficioDTO(123, 25000, '2021-01-15', '2021-01-20'),
            new BeneficioDTO(124, 15000, '2023-02-10', '2023-02-15'),
            new BeneficioDTO(125, 35000, '2022-01-10', '2022-01-15')
        ];

        $filtros = [
            new FiltroDTO(123, 'Tramite A', 20000, 30000, 1),
            new FiltroDTO(124, 'Tramite B', 10000, 20000, 2),
            new FiltroDTO(125, 'Tramite C', 30000, 40000, 3)
        ];

        $fichas = [
            new FichaDTO(1, 'Ficha A', 123, 'url1', 'Cat A', 'Desc A'),
            new FichaDTO(2, 'Ficha B', 124, 'url2', 'Cat B', 'Desc B'),
            new FichaDTO(3, 'Ficha C', 125, 'url3', 'Cat C', 'Desc C')
        ];

        $this->beneficiosRepository->method('getAll')->willReturn($beneficios);
        $this->filtrosRepository->method('getAll')->willReturn($filtros);
        $this->fichasRepository->method('getAll')->willReturn($fichas);

        // Act
        $resultado = $this->service->procesarBeneficios();

        // Assert
        $this->assertCount(3, $resultado);
        $this->assertEquals('2023', $resultado[0]['ano']); // Más reciente primero
        $this->assertEquals('2022', $resultado[1]['ano']);
        $this->assertEquals('2021', $resultado[2]['ano']); // Más antiguo último
    }
} 