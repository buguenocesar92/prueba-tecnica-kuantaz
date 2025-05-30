<?php

namespace App\Repositories\Interfaces;

use App\DTOs\FiltroDTO;

interface FiltrosRepositoryInterface
{
    /**
     * Obtiene todos los filtros desde la fuente externa
     *
     * @return FiltroDTO[]
     *
     * @throws \Exception
     */
    public function getAll(): array;
}
