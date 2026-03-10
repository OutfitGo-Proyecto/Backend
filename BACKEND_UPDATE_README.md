# Actualización del Backend (Autenticación y Carrito)

Este documento resume todos los cambios y archivos creados para proporcionar una API RESTful estructurada, segura y escalable para la tienda online.

## 🛠️ Resumen de Cambios

### 1. Sistema de Autenticación con Laravel Sanctum
- **`app/Http/Controllers/Api/AuthController.php`**: Controlador responsable del registro, inicio y cierre de sesión de usuarios.
- **`app/Http/Requests/Auth/RegisterRequest.php`**: Form Request con validaciones fuertes para registrar usuarios (contraseñas seguras, email único).
- **`app/Http/Requests/Auth/LoginRequest.php`**: Validación de credenciales de ingreso.
- Modificación en **`app/Models/User.php`** para incluir el trait `HasApiTokens` que permite la generación de tokens `access_token` y añadir relaciones.

### 2. Carrito de Compras
- **Migraciones**:
  - `database/migrations/2026...create_cart_items_table.php` para la tabla `cart_items`.
- **Modelos**:
  - `app/Models/CartItem.php` con relaciones hacia `User` y `Producto`.
- **API y Lógica**:
  - **`app/Http/Controllers/Api/CartController.php`**: Permite ver el carrito (`index`), añadir un producto comprobando el stock (`store`) y eliminar un item (`destroy`).
  - **`app/Http/Resources/CartItemResource.php`**: Eloquent Resource para formatear la respuesta enviada al cliente frontend y mostrar detalles del producto (incluyendo su modelo y relaciones).

### 3. Sistema de Órdenes y Checkout (Transaccional)
- **Migraciones**:
  - `database/migrations/2026...create_orders_table.php` (tabla principal del pedido).
  - `database/migrations/2026...create_order_items_table.php` (detalle de productos por pedido).
- **Modelos**:
  - `app/Models/Order.php` y `app/Models/OrderItem.php`.
- **API y Lógica**:
  - **`app/Http/Controllers/Api/CheckoutController.php`**: Lógica de checkout segura envuelta en una `DB::transaction`. Comprueba el stock actual, calcula el total, inserta los datos en las tablas de ruteo pertinentes `orders` y `order_items`, resta el stock del producto real en la base de datos y finalmente vacía el carrito del usuario autenticado. 

### 4. Rutas
- Actualización en **`routes/api.php`**:
  - **Rutas públicas**: `POST /api/register`, `POST /api/login`.
  - **Rutas protegidas por Sanctum (`auth:sanctum`)**: Acceso al carrito `GET|POST|DELETE /api/cart`, `POST /api/checkout`, y `POST /api/logout`. Relacionando cada acción de forma segura a la sesión activa mediante el `access_token` proporcionado al logear.

---

> **Nota:** Recuerda correr `php artisan migrate` después de estos cambios para que las tablas en tu base de datos sean creadas.
