<?php

namespace App\DTOs;

class FichaDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombre,
        public readonly int $id_programa,
        public readonly string $url,
        public readonly string $categoria,
        public readonly string $descripcion
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            nombre: $data['nombre'],
            id_programa: $data['id_programa'],
            url: $data['url'],
            categoria: $data['categoria'],
            descripcion: $data['descripcion']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'id_programa' => $this->id_programa,
            'url' => $this->url,
            'categoria' => $this->categoria,
            'descripcion' => $this->descripcion,
        ];
    }
}
