<?php

namespace App\Repositories\External;

use App\DTOs\FiltroDTO;
use App\Repositories\Interfaces\FiltrosRepositoryInterface;
use Illuminate\Support\Facades\Http;

class FiltrosRepository implements FiltrosRepositoryInterface
{
    private string $apiUrl;
    private int $timeout;

    public function __construct()
    {
        $this->apiUrl = env('FILTROS_API_URL', 'https://run.mocky.io/v3/b0ddc735-cfc9-410e-9365-137e04e33fcf');
        $this->timeout = 30;
    }

    public function getAll(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get($this->apiUrl);
            
            if (!$response->successful()) {
                throw new \Exception("Error al obtener filtros: HTTP {$response->status()}");
            }

            $data = $response->json();
            
            if (!isset($data['data']) || !is_array($data['data'])) {
                throw new \Exception("Formato de respuesta inválido para filtros");
            }

            return array_map(
                fn(array $item) => FiltroDTO::fromArray($item),
                $data['data']
            );

        } catch (\Exception $e) {
            throw new \Exception("Error al obtener filtros: " . $e->getMessage());
        }
    }
} 