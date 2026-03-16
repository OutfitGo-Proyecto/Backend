# 📘 Guía de Integración Frontend (Angular)

> **Autenticación, Carrito y Checkout (ACTUALIZADA STRIPE)**

Bienvenido al módulo de compras de **OutfitGo**. Esta guía complementa a la guía del catálogo de productos y está diseñada para proporcionar al equipo de Frontend (Angular) toda la información necesaria para consumir nuestra API RESTful protegida (Auth, Cart y Checkout con pasarela de pago Stripe).

---

## 🌐 Configuración Base de Seguridad y Tokens

- **URL Base de la API**: Usa la misma URL base (`http://localhost:8000/api` para entorno local) o `http://34.229.141.169:8000/api` si la máquina está encendida en producción.
- **Mecanismo de Autenticación**: Laravel Sanctum. A diferencia de las rutas del catálogo que son públicas, las rutas de compras **requieren un Token de Acceso**.
- **Cabecera Obligatoria**: Una vez logueado el usuario, todas las peticiones a rutas privadas (Carrito y Checkout) deben enviar el siguiente header:
  ```http
  Authorization: Bearer {tu_access_token}
  ```

---

## 🔐 1. Endpoints de Autenticación (Públicos)

Estos endpoints **no requieren token** (su propósito es generar o destruir tokens).

### 1.1 Registro de Usuario
**`POST /api/register`**

**Body (JSON):**
```json
{
  "name": "Juan Perez",
  "email": "juan@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!"
}
```

* **Respuesta Exitosa (`201 Created`)**: Devuelve el objeto del usuario y el token de acceso.

### 1.2 Inicio de Sesión
**`POST /api/login`**

**Body (JSON):**
```json
{
  "email": "juan@example.com",
  "password": "Password123!"
}
```

* **Respuesta Exitosa (`200 OK`)**:
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

> **📌 Nota Frontend**: Debes guardar este `access_token` en `localStorage` o en tu manejador de estado (NgRx, Signals, etc.) y enviarlo en los interceptores HTTP de Angular para las siguientes llamadas privadas.

---

## 🛒 2. Endpoints del Carrito de Compras (Privados 🔒)

> **Requieren Header**: `Authorization: Bearer {token}`

### 2.1 Obtener o Ver el Carrito
**`GET /api/cart`**

* **Funcionalidad**: Devuelve un array con todos los productos guardados por el usuario autenticado.
* **Respuesta Exitosa (`200 OK`)**:
```json
{
    "data": [
        {
            "id": 15,
            "cantidad": 2,
            "subtotal": 323.80,
            "producto": {
                "id": 1,
                "nombre": "Zapatillas Nike Air",
                "slug": "zapatillas-nike-air",
                "precio": "161.90",
                "url_imagen_principal": "https://...",
                "stock": 25
            }
        }
    ]
}
```

> **💡 Consejo de UI**: Puesto que devolvemos un bloque `subtotal` ya calculado en cada iteración del carrito, el diseño del Frontend de la pantalla "Tu Carrito" puede sumar fácilmente el monto total general usando un `.reduce()` sobre los ítems antes de proceder al checkout.

### 2.2 Añadir Producto al Carrito
**`POST /api/cart`**

**Body (JSON):**
```json
{
  "producto_id": 1,
  "cantidad": 1
}
```

* **Respuesta Exitosa (`201 Created`)**: Devuelve un mensaje de confirmación y el ítem insertado.

### 2.3 Eliminar Ítem del Carrito
**`DELETE /api/cart/{id_del_cart_item}`**

* **Ejemplo**: `DELETE /api/cart/15`
* **Respuesta Exitosa (`200 OK`)**: Confirma la eliminación del ítem.

---

## 💳 3. Endpoints de Checkout con Stripe (Privados 🔒)

**¡NUEVO FLUJO!** Ya no procesamos el pago de forma síncrona en un solo paso. Ahora el proceso consta de **2 pasos obligatorios** porque redirigimos al usuario a Stripe de forma segura.

### 3.1 PASO 1: Iniciar Pago y Redirigir a Stripe
**`POST /api/checkout/iniciar`**

* **Funcionalidad**: Recibe los datos de envío (**NO pedir tarjeta de crédito en Angular**), revisa el stock, crea el pedido en estado *"Pendiente"* y devuelve un enlace seguro de pago hacia Stripe.

**Body (JSON):**
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez García",
  "telefono": "600123456",
  "direccion": "Calle Falsa 123, 3ºB",
  "ciudad": "Madrid",
  "provincia": "Madrid",
  "codigo_postal": "28080",
  "notas": "Dejar en conserjería"
}
```

* **Respuesta Exitosa (`200 OK`)**:
```json
{
    "url": "https://checkout.stripe.com/pay/cs_test_a1b2c3d4..."
}
```

> **🔥 ACCIÓN FRONTEND**: Cuando recibas este `200 OK`, debes hacer un `window.location.href = respuesta.url` para sacar al usuario de la aplicación Angular y llevarlo a la página segura de pago de Stripe.

### 3.2 PASO 2: Confirmar Pago (Pantalla "Pago Exitoso")
**`POST /api/checkout/confirmar`**

* **Funcionalidad**: Cuando el usuario paga en Stripe, es devuelto automáticamente a Angular a una ruta similar a `/pago-exitoso?session_id=...`. Esta petición le dice a Laravel que verifique con Stripe si realmente se completó el pago para cambiar el pedido a *"Completado"*, vaciar el carrito y restar el stock.

**Body (JSON):** *(Angular debe leer este `session_id` de los query parameters en la URL o barra de direcciones)*
```json
{
  "session_id": "cs_test_a1b2c3d4..." 
}
```

* **Respuesta Exitosa (`200 OK`)**:
```json
{
    "message": "¡Pago verificado y compra completada con éxito!",
    "order": {
        "id": 105,
        "total": "323.80",
        "estado": "pagado"
    }
}
```

> **🔥 ACCIÓN FRONTEND**: Mientras esperas la respuesta de este `POST`, es fundamental mostrar un indicador de carga (spinner / "Cargando..."). **Solo cuando recibas el `200 OK`**, muestra el mensaje definitivo de "¡Compra completada!".

---

## 🚪 4. Otros Endpoints Útiles (Privados 🔒)

### Cerrar Sesión (Destruir Token)
**`POST /api/logout`**

Destruye el token del usuario actual en el servidor. 
* **Recomendación Frontend**: Llamar a este endpoint al pulsar "Cerrar sesión" en la UI y posteriomente limpiar las cookies o el `localStorage` en Angular para borrar cualquier rastro de la sesión local.

### Obtener Perfil Usuario Actual
**`GET /api/user`**

Devuelve los datos del usuario que actualmente tiene sesión activa (muy útil para validar rápidamente si un token guardado sigue siendo válido y traer de vuelta su información tras recargar la página).

---

## 📝 Reglas de Negocio a tener en cuenta en la UI y Errores

### Manejo de Errores Vía HTTP Codes:

* 🔴 **`401 Unauthorized`**: El token no fue enviado, caducó, o es incorrecto. Saca al usuario de la sesión, limpia el `localStorage` y redirige a la pantalla de Login.
* 🟡 **`422 Unprocessable Entity`**: Significa un error de validación de formulario o un problema de negocio (por ejemplo, stock insuficiente al intentar iniciar el pago). Tu UI debe mostrar alertas formales o *toasts* con este error puntual extraído de la respuesta JSON para informar al usuario de que algo ha ido mal.

### Protección de Stock
> ⚠️ **Atención**: El stock **NO** se reserva mientras el producto está en el carrito, ni siquiera cuando el usuario está en Stripe introduciendo sus datos de pago. El stock **se descuenta definitivamente en el Paso 2 (Confirmar Pago)**. Si otro cliente es más rápido comprando la última unidad, Laravel rechazará la confirmación, avisará con un error y no se le cobrará.


---

## 📋 ANEXO: Nuevos Campos de Envío (Base de Datos)

Debido a la actualización de la tabla `orders`, el formulario de "Datos de Envío" en Angular ahora debe capturar y enviar obligatoriamente los siguientes campos.

> **Importante**: Estos campos se envían en el **PASO 1** (`POST /api/checkout/iniciar`).

| Campo | Tipo | Descripción |
| :--- | :--- | :--- |
| `nombre` | String | Nombre del destinatario. |
| `apellidos` | String | Apellidos del destinatario. |
| `telefono` | String | Teléfono de contacto (importante para la mensajería). |
| `direccion` | String | Calle, número, piso y puerta. |
| `ciudad` | String | Ciudad de destino. |
| `provincia` | String | Provincia o región. |
| `codigo_postal`| String | Código postal (máximo 10 caracteres). |
| `notas` | Text | *(Opcional)* Notas para el repartidor (ej: "Llamar al timbre 2"). |

**Ejemplo de objeto JSON actualizado para el Checkout:**

```json
{
  "nombre": "Ana",
  "apellidos": "García López",
  "telefono": "611223344",
  "direccion": "Avenida de la Constitución 45, 2ºC",
  "ciudad": "Valencia",
  "provincia": "Valencia",
  "codigo_postal": "46001",
  "notas": "Si no estoy, dejar en el bar de abajo."
}
```

> **📌 Nota para Frontend**: Si falta cualquiera de estos campos (excepto `notas`), la API devolverá un error `422 Unprocessable Entity` indicando qué campo falta. Asegúrate de implementar validaciones en el formulario de Angular para que el usuario no deje nada vacío.