<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulacion;
use App\Models\VacanteTrabajo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostulacionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        $postulaciones = Postulacion::with('vacante', 'postulante')
            ->whereHas('vacante', function ($query) use ($user) {
            $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($postulaciones->isEmpty()) {
            return response()->json(['message' => 'No se encontraron postulaciones.'], 404);
        }

        return response()->json([
            'postulaciones' => $postulaciones
        ], 200);
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $validated = $request->validate([
            'vacantetrabajo_id' => 'required|exists:vacantetrabajos,id',
            'telefono' => 'required|string|max:20', // Aseguramos que el teléfono es requerido
            'curriculum_vitae' => 'required|file|mimes:pdf,doc,docx|max:2048', // CV: requerido, tipo de archivo, tamaño máximo
        ]);

        $rutaCv = null;
        if ($request->hasFile('curriculum_vitae')) {
            $file = $request->file('curriculum_vitae');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $rutaCv = $file->storeAs('curriculums', $nombreArchivo, 'public');
        }

        $postulacion = Postulacion::create([
            'vacantetrabajo_id' => $validated['vacantetrabajo_id'],
            'user_id' => $userId,
            'telefono' => $validated['telefono'],
            'ruta_cv' => $rutaCv,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Postulación creada exitosamente',
            'data' => $postulacion->load('vacante', 'postulante')
        ], 201);
    }

    public function updateStatus(Request $request, string $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        $request->validate([
            'estado' => ['required', 'string', 'in:aceptada,rechazada'],
        ]);

        $postulacion = Postulacion::with('vacante')->find($id);

        if (!$postulacion) {
            return response()->json(['message' => 'Postulación no encontrada.'], 404);
        }

        $isOwner = $postulacion->vacante && $postulacion->vacante->user_id === $user->id;
        $isAdmin = ($user->rol === 'admin');

        if (!$isOwner && !$isAdmin) {
            return response()->json(['message' => 'No autorizado para actualizar esta postulación.'], 403);
        }

        $postulacion->estado = $request->estado;
        $postulacion->save();

        return response()->json([
            'message' => 'Estado de la postulación actualizado correctamente.',
            'data' => $postulacion->load('vacante', 'postulante')
        ]);
    }

}