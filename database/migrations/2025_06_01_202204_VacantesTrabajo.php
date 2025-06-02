<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vacantetrabajos', function (Blueprint $table) {
            $table->id();
            $table->string('Titulo');
            $table->text('Descripcion');
            $table->string('Compañia');
            $table->string('Localizacion');
            $table->decimal('Salario')->nullable();
            $table->string('Tipo_Contrato')->default('full_time');
            $table->string('Nivel_Experiencia')->nullable();
            $table->string('Habilidades')->nullable();
            $table->timestamp('Fecha_Publicacion')->nullable();
            $table->timestamp('Expiracion')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('Estado_vacante')->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
