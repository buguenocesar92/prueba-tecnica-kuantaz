<?php

namespace Tests\Unit\DTOs;

use App\DTOs\FiltroDTO;
use PHPUnit\Framework\TestCase;

class FiltroDTOTest extends TestCase
{
    public function test_from_array_creates_filtro_dto_correctly(): void
    {
        $data = [
            'id_programa' => 147,
            'tramite' => 'Emprende',
            'min' => 10000,
            'max' => 50000,
            'ficha_id' => 922,
        ];

        $filtro = FiltroDTO::fromArray($data);

        $this->assertInstanceOf(FiltroDTO::class, $filtro);
        $this->assertEquals(147, $filtro->id_programa);
        $this->assertEquals('Emprende', $filtro->tramite);
        $this->assertEquals(10000, $filtro->min);
        $this->assertEquals(50000, $filtro->max);
        $this->assertEquals(922, $filtro->ficha_id);
    }

    public function test_to_array_returns_correct_array(): void
    {
        $filtro = new FiltroDTO(
            id_programa: 147,
            tramite: 'Emprende',
            min: 10000,
            max: 50000,
            ficha_id: 922
        );

        $array = $filtro->toArray();

        $expected = [
            'id_programa' => 147,
            'tramite' => 'Emprende',
            'min' => 10000,
            'max' => 50000,
            'ficha_id' => 922,
        ];

        $this->assertEquals($expected, $array);
    }

    public function test_is_monto_valid_returns_true_for_valid_amount(): void
    {
        $filtro = new FiltroDTO(
            id_programa: 147,
            tramite: 'Emprende',
            min: 10000,
            max: 50000,
            ficha_id: 922
        );

        $this->assertTrue($filtro->isMontoValid(25000));
        $this->assertTrue($filtro->isMontoValid(10000)); // Límite inferior
        $this->assertTrue($filtro->isMontoValid(50000)); // Límite superior
    }

    public function test_is_monto_valid_returns_false_for_invalid_amount(): void
    {
        $filtro = new FiltroDTO(
            id_programa: 147,
            tramite: 'Emprende',
            min: 10000,
            max: 50000,
            ficha_id: 922
        );

        $this->assertFalse($filtro->isMontoValid(5000));  // Menor al mínimo
        $this->assertFalse($filtro->isMontoValid(60000)); // Mayor al máximo
        $this->assertFalse($filtro->isMontoValid(9999));  // Justo debajo del mínimo
        $this->assertFalse($filtro->isMontoValid(50001)); // Justo arriba del máximo
    }
} 