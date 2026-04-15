<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;">
        
        <h2 style="color: #2563eb;">¡Hola, {{ $pedido->user->name }}!</h2>
        
        <p>Tu pago se ha procesado correctamente. ¡Gracias por confiar en OutfitGo!</p>
        
        <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px;">
            <h3>Resumen de tu pedido #{{ $pedido->id }}</h3>
            <p><strong>Total pagado:</strong> {{ $pedido->total }} €</p>
            <p><strong>Dirección de envío:</strong> {{ $pedido->direccion }}, {{ $pedido->ciudad }}</p>
        </div>

        <p style="margin-top: 20px;">En breve recibirás otro correo cuando tu paquete salga de nuestro almacén.</p>
        
        <hr>
        <p style="font-size: 12px; color: #777;">© 2026 OutfitGo. Este es un correo automático, por favor no respondas.</p>
    </div>
</body>
</html>