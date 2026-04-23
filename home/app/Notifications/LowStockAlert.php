<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ProductoVariante;

class LowStockAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $variante;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProductoVariante $variante)
    {
        $this->variante = $variante;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Aseguramos que tenemos las relaciones cargadas
        $this->variante->loadMissing(['producto', 'talla', 'color']);

        $productoNombre = $this->variante->producto->nombre ?? 'Producto Desconocido';
        $talla = $this->variante->talla->nombre ?? 'N/A';
        $color = $this->variante->color->nombre ?? 'N/A';
        $stock = $this->variante->stock;

        return (new MailMessage)
            ->subject('¡Alerta de Stock Bajo! ' . $productoNombre)
            ->greeting('Hola Administrador,')
            ->line('El siguiente producto se está quedando sin stock:')
            ->line("**Producto:** {$productoNombre}")
            ->line("**Variante:** Talla: {$talla} / Color: {$color}")
            ->line("**Stock Restante:** {$stock} unidades")
            ->line('Por favor, reponed el inventario lo antes posible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
