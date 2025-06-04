<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VacanteTrabajo extends Model
{
    use HasFactory;
    protected $table = 'vacantetrabajos';
    protected $fillable = [
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
    ];

    protected $casts = [
        'Habilidades' => 'array',
        'Fecha_Publicacion' => 'datetime',
        'Expiracion' => 'datetime',
    ];
}
