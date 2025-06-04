<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\VacanteTrabajo;

class VacanteTrabajoTest extends TestCase
{
    /** @test */
    public function puede_guardar_una_vacante()
    {
        $vacante = new VacanteTrabajo([
            'Titulo' => 'Backend Developer',
            'Descripcion' => 'Conocimiento en Laravel',
            'Compania' => 'Tech Corp',
            'Localizacion' => 'Remoto',
            'Salario' => 50000,
            'Tipo_Contrato' => 'full_time',
            'Nivel_Experiencia' => 'junior',
            'Habilidades' => json_encode(['Laravel', 'PHP', 'MySQL']),
            'Estado_vacante' => 'activo',
        ]);

        $this->assertEquals('Backend Developer', $vacante->Titulo);
        $this->assertEquals(50000, $vacante->Salario);
    }
}
