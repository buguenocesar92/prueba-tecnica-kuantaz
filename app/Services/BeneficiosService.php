<?php

namespace App\Services;

use App\DTOs\BeneficioDTO;
use App\Repositories\Interfaces\BeneficiosRepositoryInterface;
use App\Repositories\Interfaces\FichasRepositoryInterface;
use App\Repositories\Interfaces\FiltrosRepositoryInterface;
use Illuminate\Support\Collection;

class BeneficiosService
{
    public function __construct(
        private readonly BeneficiosRepositoryInterface $beneficiosRepository,
        private readonly FiltrosRepositoryInterface $filtrosRepository,
        private readonly FichasRepositoryInterface $fichasRepository
    ) {}

    /**
     * Procesa los beneficios aplicando filtros y agrupando por año
     *
     * @throws \Exception
     */
    public function procesarBeneficios(): array
    {
        // Obtener datos de todas las fuentes
        $beneficios = $this->beneficiosRepository->getAll();
        $filtros = $this->filtrosRepository->getAll();
        $fichas = $this->fichasRepository->getAll();

        // Crear índices para optimizar búsquedas
        $filtrosIndexados = $this->indexarFiltrosPorPrograma($filtros);
        $fichasIndexadas = $this->indexarFichasPorId($fichas);

        // Procesar beneficios
        $beneficiosProcesados = $this->aplicarFiltrosYFichas($beneficios, $filtrosIndexados, $fichasIndexadas);

        // Agrupar por año y calcular totales
        return $this->agruparPorAnoYCalcularTotales($beneficiosProcesados);
    }

    /**
     * Indexa filtros por id_programa para búsqueda O(1)
     */
    private function indexarFiltrosPorPrograma(array $filtros): Collection
    {
        return collect($filtros)->keyBy('id_programa');
    }

    /**
     * Indexa fichas por id para búsqueda O(1)
     */
    private function indexarFichasPorId(array $fichas): Collection
    {
        return collect($fichas)->keyBy('id');
    }

    /**
     * Aplica filtros de monto y asocia fichas a los beneficios
     */
    private function aplicarFiltrosYFichas(
        array $beneficios,
        Collection $filtrosIndexados,
        Collection $fichasIndexadas
    ): array {
        $beneficiosValidos = [];

        foreach ($beneficios as $beneficio) {
            $filtro = $filtrosIndexados->get($beneficio->id_programa);
            // Verificar si existe filtro y si el monto es válido
            if (! $filtro) {
                continue;
            }
            if (! $filtro->isMontoValid($beneficio->monto)) {
                continue;
            }

            // Buscar y asociar ficha
            $ficha = $fichasIndexadas->get($filtro->ficha_id);
            if (! $ficha) {
                continue;
            }

            // Agregar año y ficha al beneficio
            $beneficioConAnoYFicha = $beneficio
                ->withAno((string) $beneficio->getYear())
                ->withFicha($ficha);

            $beneficiosValidos[] = $beneficioConAnoYFicha;
        }

        return $beneficiosValidos;
    }

    /**
     * Agrupa beneficios por año y calcula totales
     */
    private function agruparPorAnoYCalcularTotales(array $beneficios): array
    {
        return collect($beneficios)
            ->groupBy('ano')
            ->map(function (Collection $beneficiosPorAno, string $ano): array {
                $beneficiosOrdenados = $beneficiosPorAno
                    ->sortBy('fecha')
                    ->values()
                    ->map(fn (BeneficioDTO $beneficio): array => $beneficio->toArray())
                    ->toArray();

                return [
                    'ano' => $ano,
                    'total_monto' => $beneficiosPorAno->sum('monto'),
                    'num' => $beneficiosPorAno->count(),
                    'beneficios' => $beneficiosOrdenados,
                ];
            })
            ->sortByDesc('ano')
            ->values()
            ->toArray();
    }
}
