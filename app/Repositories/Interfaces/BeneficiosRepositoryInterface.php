<?php

namespace App\Repositories\Interfaces;

use App\DTOs\BeneficioDTO;

interface BeneficiosRepositoryInterface
{
    /**
     * Obtiene todos los beneficios desde la fuente externa
     * 
     * @return BeneficioDTO[]
     * @throws \Exception
     */
    public function getAll(): array;
} 