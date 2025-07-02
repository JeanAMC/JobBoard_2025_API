<?php
// ...existing code...
use App\Notifications\EstadoPostulacionNotification;
use App\Models\User;
use App\Models\Postulacion;

public function cambiarEstado(Request $request, $id)
{
    $postulacion = Postulacion::findOrFail($id);
    $nuevoEstado = $request->input('estado'); // 'aceptada' o 'rechazada'
    $mensajePersonalizado = $request->input('mensaje');

    $postulacion->estado = $nuevoEstado;
    $postulacion->save();

    // Busca el usuario relacionado a la postulación
    $usuario = User::find($postulacion->user_id);

    // Envía la notificación por correo
    if ($usuario && $usuario->email) {
        $usuario->notify(new EstadoPostulacionNotification($nuevoEstado, $mensajePersonalizado));
    }

    return response()->json(['message' => 'Estado actualizado y correo enviado']);
}
// ...existing code...