<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulaciones';

    protected $fillable = [
        'vacantetrabajo_id',
        'user_id',
        'telefono',
        'ruta_cv',
        'estado',
        'fecha_postulacion',
    ];

    protected $casts = [
        'fecha_postulacion' => 'datetime',
    ];

    public function vacante()
    {
        return $this->belongsTo(VacanteTrabajo::class, 'vacantetrabajo_id');
    }

    public function postulante()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}