<?php

namespace App\DTOs;

class BeneficioDTO
{
    public function __construct(
        public readonly int $id_programa,
        public readonly int $monto,
        public readonly string $fecha_recepcion,
        public readonly string $fecha,
        public readonly ?string $ano = null,
        public readonly bool $view = true,
        public readonly ?FichaDTO $ficha = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id_programa: $data['id_programa'],
            monto: $data['monto'],
            fecha_recepcion: $data['fecha_recepcion'],
            fecha: $data['fecha'],
            ano: $data['ano'] ?? null,
            view: $data['view'] ?? true,
            ficha: isset($data['ficha']) ? FichaDTO::fromArray($data['ficha']) : null
        );
    }

    public function toArray(): array
    {
        return [
            'id_programa' => $this->id_programa,
            'monto' => $this->monto,
            'fecha_recepcion' => $this->fecha_recepcion,
            'fecha' => $this->fecha,
            'ano' => $this->ano,
            'view' => $this->view,
            'ficha' => $this->ficha?->toArray(),
        ];
    }

    public function withAno(string $ano): self
    {
        return new self(
            id_programa: $this->id_programa,
            monto: $this->monto,
            fecha_recepcion: $this->fecha_recepcion,
            fecha: $this->fecha,
            ano: $ano,
            view: $this->view,
            ficha: $this->ficha
        );
    }

    public function withFicha(FichaDTO $ficha): self
    {
        return new self(
            id_programa: $this->id_programa,
            monto: $this->monto,
            fecha_recepcion: $this->fecha_recepcion,
            fecha: $this->fecha,
            ano: $this->ano,
            view: $this->view,
            ficha: $ficha
        );
    }

    public function getYear(): int
    {
        return (int) date('Y', strtotime($this->fecha));
    }
}
