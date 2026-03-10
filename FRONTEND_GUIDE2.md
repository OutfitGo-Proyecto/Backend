# 📘 Guía de Integración Frontend (Angular) -> Autenticación, Carrito y Checkout

Bienvenido al módulo de compras de OutfitGo. Esta guía complementa a la guía del catálogo de productos y está diseñada para proporcionar al equipo de Frontend (Angular) toda la información necesaria para consumir nuestra API RESTful protegida (Auth, Cart y Checkout).

## 🌐 Configuración Base de Seguridad y Tokens

*   **URL Base de la API**: Usa la misma URL base (`http://localhost:8000/api` para entorno local)/ o `http://34.229.141.169:8000/api` si la maquina esta encendida.
*   **Mecanismo de Autenticación**: Laravel Sanctum. A diferencia de las rutas del catálogo que son públicas, **las rutas de compras requieren un Token de Acceso**.
*   **Cabecera Obligatoria**: Una vez logueado el usuario, **todas** las peticiones a rutas privadas (Carrito y Checkout) deben enviar el header:
    `Authorization: Bearer {tu_access_token}`

---

## 🔐 1. Endpoints de Autenticación (Públicos)

Estos endpoints no requieren token (generan o destruyen tokens).

### 1.1 Registro de Usuario
**`POST /api/register`**
*   **Body (JSON)**:
    ```json
    {
      "name": "Juan Perez",
      "email": "juan@example.com",
      "password": "Password123!",
      "password_confirmation": "Password123!"
    }
    ```
*   **Respuesta Exitosa (201 Created)**: Devuelve el objeto del usuario y el token.

### 1.2 Inicio de Sesión
**`POST /api/login`**
*   **Body (JSON)**:
    ```json
    {
      "email": "juan@example.com",
      "password": "Password123!"
    }
    ```
*   **Respuesta Exitosa (200 OK)**:
    ```json
    {
        "message": "Inicio de sesión exitoso",
        "user": {
            "id": 1,
            "name": "Juan Perez",
            "email": "juan@example.com"
        },
        "access_token": "1|abcdef1234567890...",
        "token_type": "Bearer"
    }
    ```

> **📌 Nota Frontend**: Debes guardar este `access_token` en `localStorage` o en tu manejador de estado y enviarlo en los interceptores HTTP de Angular para las siguientes llamadas.

---

## 🛒 2. Endpoints del Carrito de Compras (Privados 🔒)

**Requieren Header**: `Authorization: Bearer {token}`

### 2.1 Obtener o Ver el Carrito
**`GET /api/cart`**
*   **Funcionalidad**: Devuelve un array con todos los productos guardados por el usuario autenticado.
*   **Respuesta Exitosa (200 OK)**:
    ```json
    {
        "data": [
            {
                "id": 15,
                "cantidad": 2,
                "subtotal": 323.80,
                "producto": {
                    "id": 1,
                    "nombre": "Fugit eum sed blanditiis.",
                    "slug": "non-ullam-omnis-temporibus",
                    "precio": "161.90",
                    "url_imagen_principal": "https://via.placeholder.com/640x480.png...",
                    "stock": 25
                },
                "creado_en": "2026-03-10T12:00:00.000000Z",
                "actualizado_en": "2026-03-10T12:00:00.000000Z"
            }
        ]
    }
    ```

### 2.2 Añadir Producto al Carrito
**`POST /api/cart`**
*   **Body (JSON)**:
    ```json
    {
      "producto_id": 1,
      "cantidad": 1
    }
    ```
*   **Respuesta Exitosa (201 Created)**: Devuelve el mensaje y el ítem insertado.
*   **Validación Posterior (HTTP 422)**: Si no hay suficiente stock en backend (o en el carrito acumulado), el servidor responderá con HTTP Status 422 y un mensaje detallado explicando el error de stock.

### 2.3 Eliminar Ítem del Carrito
**`DELETE /api/cart/{id_del_cart_item}`**
*   **Ejemplo**: `DELETE /api/cart/15`
*   **Respuesta Exitosa (200 OK)**:
    ```json
    {
        "message": "Producto eliminado del carrito exitosamente"
    }
    ```

---

## 💳 3. Endpoint de Checkout (Privado 🔒)

**Requieren Header**: `Authorization: Bearer {token}`

Este endpoint procesa el pago simulando la compra final. **Convierte los ítems del carrito en un Pedido (Order), resta el stock real de los productos y vacía el carrito automáticamente.**

**`POST /api/checkout`**
*   **Body**: Ninguno. (El servidor calcula todo usando el carrito actual del usuario).
*   **Respuesta Exitosa (201 Created)**:
    ```json
    {
        "message": "Compra realizada con éxito",
        "order": {
            "id": 105,
            "user_id": 1,
            "total": "323.80",
            "estado": "completado",
            "created_at": "2026-03-10T12:15:00.000000Z",
            "order_items": [
                {
                    "id": 402,
                    "order_id": 105,
                    "producto_id": 1,
                    "cantidad": 2,
                    "precio_unitario": "161.90",
                    "producto": {
                         "id": 1,
                         "nombre": "Fugit eum sed blanditiis."
                         // ...
                    }
                }
            ]
        }
    }
    ```

---

## 🚪 Otros Endpoints Útiles (Privados 🔒)

### Cerrar Sesión (Destruir Token)
**`POST /api/logout`**
*   Destruye el token del usuario actual en el servidor. Recomendado realizarlo al pulsar "Cerrar sesión" en la UI y luego limpiar las cookies/localStorage en Frontend.

### Obtener Perfil Usuario Actual
**`GET /api/user`**
*   Devuelve los datos del usuario que actualmente tiene sesión (valida rápidamente si un token es válido).

---

## 📝 Reglas de Negocio a tener en cuenta en la UI y Errores

1.  **Manejo de Errores Vía HTTP Codes**:
    *   **401 Unauthorized**: El token no fue enviado, caducó, o es incorrecto. Saca al usuario de la sesión en tu lado (Angular) y redirige a la vista de Login.
    *   **422 Unprocessable Entity**: Significa un error de validación o un problema de negocio (por ejemplo, el usuario solicitó 5 zapatillas pero el stock backend es 3). En la respuesta JSON vendrá el motivo del error. Tu UI debe mostrar alertas o _toasts_ con este error puntual.
2.  **Protección de Stock**: El stock no se reserva mientras el objeto descansa en el carrito. La transaccionalidad total se da en el endpoint `POST /api/checkout` mediante un bloqueo transaccional de Base de Datos. Así garantizamos consistencia si hay alta concurrencia. Tu UI no debe preocuparse por los cálculos raros; si algo falla, Backend detendrá la ejecución devolviéndote un `422`. Muestra al usuario un alert/snackBar si esto ocurre.
3.  **Visualización Amigable de Totales**: Puesto que devolvemos un bloque `subtotal` ya calculado en el cart, el diseño del Frontend de la pantalla "Tu Carrito" puede sumar fácilmente el total general usando un `.reduce()` sobre los iteradores antes de proceder al checkout.
