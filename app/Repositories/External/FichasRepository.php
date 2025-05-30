<?php

namespace App\Repositories\External;

use App\DTOs\FichaDTO;
use App\Repositories\Interfaces\FichasRepositoryInterface;
use Illuminate\Support\Facades\Http;

class FichasRepository implements FichasRepositoryInterface
{
    private string $apiUrl;
    private int $timeout;

    public function __construct()
    {
        $this->apiUrl = env('FICHAS_API_URL', 'https://run.mocky.io/v3/4654cafa-58d8-4846-9256-79841b29a687');
        $this->timeout = 30;
    }

    public function getAll(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get($this->apiUrl);
            
            if (!$response->successful()) {
                throw new \Exception("Error al obtener fichas: HTTP {$response->status()}");
            }

            $data = $response->json();
            
            if (!isset($data['data']) || !is_array($data['data'])) {
                throw new \Exception("Formato de respuesta inválido para fichas");
            }

            return array_map(
                fn(array $item) => FichaDTO::fromArray($item),
                $data['data']
            );

        } catch (\Exception $e) {
            throw new \Exception("Error al obtener fichas: " . $e->getMessage());
        }
    }
} 