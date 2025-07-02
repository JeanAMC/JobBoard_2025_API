<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EstadoPostulacionNotification extends Notification
{
    use Queueable;

    protected $estado;
    protected $mensajePersonalizado;
    protected $vacanteTitulo;
    protected $postulacionId;
    protected $vacanteId;

    public function __construct(string $estado, \App\Models\Postulacion $postulacion, ?string $mensajePersonalizado = null)
    {
        $this->estado = $estado;
        $this->mensajePersonalizado = $mensajePersonalizado ?? '';
        $this->vacanteTitulo = $postulacion->vacante->titulo ?? 'Sin título';
        $this->postulacionId = $postulacion->id;
        $this->vacanteId = $postulacion->vacante->id ?? null;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Estado de tu postulación: ' . ucfirst($this->estado))
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu postulación para la vacante **' . $this->vacanteTitulo . '** ha sido ' . $this->estado . '.')
            ->line($this->mensajePersonalizado)
            ->line('¡Gracias por usar nuestra plataforma!');
    }

    public function toArray($notifiable)
    {
        return [
            'mensaje' => $this->mensajePersonalizado,
            'estado' => $this->estado,
            'postulacion_id' => $this->postulacionId,
            'vacante_id' => $this->vacanteId,
        ];
    }
}
