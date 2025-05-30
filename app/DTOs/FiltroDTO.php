<?php

namespace App\DTOs;

class FiltroDTO
{
    public function __construct(
        public readonly int $id_programa,
        public readonly string $tramite,
        public readonly int $min,
        public readonly int $max,
        public readonly int $ficha_id
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id_programa: $data['id_programa'],
            tramite: $data['tramite'],
            min: $data['min'],
            max: $data['max'],
            ficha_id: $data['ficha_id']
        );
    }

    public function toArray(): array
    {
        return [
            'id_programa' => $this->id_programa,
            'tramite' => $this->tramite,
            'min' => $this->min,
            'max' => $this->max,
            'ficha_id' => $this->ficha_id
        ];
    }

    public function isMontoValid(int $monto): bool
    {
        return $monto >= $this->min && $monto <= $this->max;
    }
} 