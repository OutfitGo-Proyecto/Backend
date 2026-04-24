<x-mail::message>
# ¡Buenas noticias!

El artículo que tenías en tu lista de favoritos vuelve a estar disponible en nuestro catálogo.

**{{ $producto->nombre }}**

¡Date prisa antes de que se vuelva a agotar!

<x-mail::button :url="$url">
Ver Producto
</x-mail::button>

Gracias por confiar en nosotros,<br>
El equipo de OutfitGo
</x-mail::message>