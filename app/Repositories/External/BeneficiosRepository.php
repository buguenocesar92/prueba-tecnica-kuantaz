<?php

namespace App\Repositories\External;

use App\DTOs\BeneficioDTO;
use App\Repositories\Interfaces\BeneficiosRepositoryInterface;
use Illuminate\Support\Facades\Http;

class BeneficiosRepository implements BeneficiosRepositoryInterface
{
    private string $apiUrl;
    private int $timeout;

    public function __construct()
    {
        $this->apiUrl = env('BENEFICIOS_API_URL', 'https://run.mocky.io/v3/8f75c4b5-ad90-49bb-bc52-f1fc0b4aad02');
        $this->timeout = 30;
    }

    public function getAll(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get($this->apiUrl);
            
            if (!$response->successful()) {
                throw new \Exception("Error al obtener beneficios: HTTP {$response->status()}");
            }

            $data = $response->json();
            
            if (!isset($data['data']) || !is_array($data['data'])) {
                throw new \Exception("Formato de respuesta inválido para beneficios");
            }

            return array_map(
                fn(array $item) => BeneficioDTO::fromArray($item),
                $data['data']
            );

        } catch (\Exception $e) {
            throw new \Exception("Error al obtener beneficios: " . $e->getMessage());
        }
    }
} 