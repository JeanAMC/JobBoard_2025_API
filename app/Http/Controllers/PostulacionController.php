<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulacion;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EstadoPostulacionNotification;
use App\Models\User;

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
            'telefono' => 'required|string|max:20',
            'curriculum_vitae' => 'required|file|mimes:pdf,doc,docx|max:2048', 
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
       // $postulacion->user->notify(new EstadoPostulacionNotification($postulacion));
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
                'mensaje' => ['nullable', 'string'],
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


            $usuario = User::find($postulacion->user_id);
            if ($usuario && $usuario->email) {
                $usuario->notify(new EstadoPostulacionNotification(
                    $request->estado,
                    $postulacion,
                    $request->mensaje
                ));
            }

            return response()->json([
                'message' => 'Estado actualizado y notificación enviada.',
                'data' => $postulacion->load('vacante', 'postulante'),
            ]);
        }




}

