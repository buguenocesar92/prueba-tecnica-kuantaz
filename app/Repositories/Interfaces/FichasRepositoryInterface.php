<?php

namespace App\Repositories\Interfaces;

use App\DTOs\FichaDTO;

interface FichasRepositoryInterface
{
    /**
     * Obtiene todas las fichas desde la fuente externa
     *
     * @return FichaDTO[]
     *
     * @throws \Exception
     */
    public function getAll(): array;
}
