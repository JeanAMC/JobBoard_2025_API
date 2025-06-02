<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacanteTrabajo;
class VacanteTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = $request->user()->id;
        // Validar los datos recibidos
        $validated = $request->validate([
            'Titulo' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'Compañia' => 'required|string|max:255',
            'Localizacion' => 'required|string|max:255',
            'Salario' => 'nullable|numeric|min:0',
            'Tipo_Contrato' => 'required|string|in:full_time,part_time,freelance',
            'Nivel_Experiencia' => 'nullable|string|in:junior,mid,senior',
            'Habilidades' => 'required|string',
            'Fecha_Publicacion' => 'nullable|date',
            'Expiracion' => 'nullable|date',
            'Estado_vacante' => 'nullable|string|in:activo,cerrado,en revisión',
        ]);

        $validated['user_id'] = $userId;  

        // Crear la vacante
        $vacante = VacanteTrabajo::create($validated);

        return response()->json([
            'message' => 'Vacante creada exitosamente',
            'data' => $vacante
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
      $vacantes = VacanteTrabajo::orderBy('created_at', 'desc')->get();

        return response()->json([
        'vacantes' => $vacantes
    ]);
    }

    public function buscarPorTitulo(Request $request)
{
    $request->validate([
        'titulo' => 'required|string',
    ]);

    $titulo = $request->titulo;

    $vacantes = VacanteTrabajo::where('Titulo', 'LIKE', '%' . $titulo . '%')->get();

    if ($vacantes->isEmpty()) {
        return response()->json(['message' => 'No se encontraron vacantes con ese título.'], 404);
    }

    return response()->json([
        'vacantes' => $vacantes,
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
