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
            Schema::create('postulaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacantetrabajo_id')->constrained('vacantetrabajos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('mensaje')->nullable();
            $table->string('estado')->default('pendiente');
            $table->timestamp('fecha_postulacion')->useCurrent();
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
