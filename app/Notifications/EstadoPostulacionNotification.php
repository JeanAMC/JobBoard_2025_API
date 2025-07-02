<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EstadoPostulacionNotification extends Notification
{
    use Queueable;

    public $estado;
    public $mensajePersonalizado;

    public function __construct($estado, $mensajePersonalizado = null)
    {
        $this->estado = $estado;
        $this->mensajePersonalizado = $mensajePersonalizado;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $estadoTexto = $this->estado === 'aceptada' ? '¡Felicidades! Tu postulación ha sido aceptada.' : 'Lamentamos informarte que tu postulación ha sido rechazada.';
        return (new MailMessage)
            ->subject('Estado de tu postulación')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line($estadoTexto)
            ->line($this->mensajePersonalizado ?? '')
            ->line('Gracias por usar JobBoard.');
    }
}