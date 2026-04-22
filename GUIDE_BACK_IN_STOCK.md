# Guía de Implementación: Notificaciones de Stock (Back in Stock)

Esta guía detalla los pasos necesarios para implementar un sistema de notificaciones automáticas por correo electrónico cuando un producto que un usuario tiene en su lista de **Favoritos** vuelve a estar disponible (stock > 0).

---

## 1. El Mailable (Correo electrónico)

Crea un nuevo Mailable que use el branding de **OutfitGo**. Como ya configuramos el logo incrustado (CID), no tienes que preocuparte por URLs externas.

**Comando:**
```bash
php artisan make:mail ProductBackInStockMail
```

**Lógica recomendada en `app/Mail/ProductBackInStockMail.php`:**
```php
public function content(): Content
{
    return new Content(
        markdown: 'emails.products.back_in_stock',
        with: [
            'producto' => $this->producto,
            'url' => config('app.url') . '/productos/' . $this->producto->slug,
        ],
    );
}
```

---

## 2. El Observador (Trigger)

Debes aprovechar el `ProductoObserver` que ya está activo en el proyecto. La lógica debe detectar cuando el stock cambia de `0` a algo mayor.

**Archivo:** `app/Observers/ProductoObserver.php`

```php
public function updated(Producto $producto): void
{
    // 1. Historial de precios (Ya implementado)
    if ($producto->wasChanged('precio')) { ... }

    // 2. Lógica de Reposición de Stock
    if ($producto->wasChanged('stock') && $producto->getOriginal('stock') == 0 && $producto->stock > 0) {
        $this->notifyUsersBackInStock($producto);
    }
}

protected function notifyUsersBackInStock(Producto $producto)
{
    // Obtener todos los favoritos de este producto incluyendo al usuario
    $favorites = $producto->favorites()->with('user')->get();

    foreach ($favorites as $favorite) {
        $user = $favorite->user;
        // Se recomienda usar un Job para no ralentizar el proceso
        Mail::to($user->email)->queue(new ProductBackInStockMail($producto));
    }
}
```

---

## 3. Consideraciones de Branding y Estilo

*   **Logo**: Al usar el componente `<x-mail::header>`, el logo premium de **OutfitGo** se cargará automáticamente gracias a la personalización que hicimos en `resources/views/vendor/mail/html/header.blade.php`.
*   **Idioma**: Recuerda redactar el contenido del Markdown del correo en español para mantener la coherencia con el resto del sistema.
*   **Colas (Queues)**: Es **muy importante** usar `Mail::queue()` o dispatchar un Job. Si un producto tiene 500 favoritos, intentar enviar 500 correos de forma síncrona hará que la base de datos o la API den timeout.

---

## 4. Pruebas Rápidas

Para probarlo manualmente sin esperar a un proceso real:
1. Pon el stock de un producto a `0` en la base de datos.
2. Añádelo a favoritos con tu cuenta de prueba.
3. Desde el panel de administración o tinker, cambia el stock a `10`.
4. Verifica que el correo llegue a tu bandeja de entrada.

---
*Documentación generada para el equipo de desarrollo de OutfitGo.*
