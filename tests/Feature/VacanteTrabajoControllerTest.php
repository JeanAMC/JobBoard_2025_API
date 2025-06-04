<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class VacanteTrabajoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_autenticado_puede_publicar_una_vacante()
    {
        // Crear un usuario
        $user = User::factory()->create();

        // Autenticar al usuario con Sanctum
        Sanctum::actingAs($user);

        // Datos de prueba para la vacante
        $data = [
            'Titulo' => 'Desarrollador Backend',
            'Descripcion' => 'Se busca desarrollador con experiencia en Laravel',
            'Compania' => 'Tech Corp',
            'Localizacion' => 'Remoto',
            'Salario' => 25000,
            'Tipo_Contrato' => 'full_time',
            'Nivel_Experiencia' => 'junior',
            'Habilidades' => 'Laravel, PHP, MySQL',
            'Fecha_Publicacion' => now()->toDateTimeString(),
            'Expiracion' => now()->addDays(30)->toDateTimeString(),
            'Estado_vacante' => 'activo',
        ];

        // Hacer la petición POST
        $response = $this->postJson('/api/PublicarVacante', $data);

        // Verificar la respuesta
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'Titulo',
                'Descripcion',
                'Compania',
                'Localizacion',
                'Salario',
                'Tipo_Contrato',
                'Nivel_Experiencia',
                'Habilidades',
                'Fecha_Publicacion',
                'Expiracion',
                'Estado_vacante',
                'user_id',
                'created_at',
                'updated_at',
            ],
        ]);

        // Verificar que la vacante esté en la base de datos
        $this->assertDatabaseHas('vacantetrabajos', [
            'Titulo' => 'Desarrollador Backend',
            'Compania' => 'Tech Corp',
        ]);
    }
}
