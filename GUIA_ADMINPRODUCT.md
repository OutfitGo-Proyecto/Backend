# 📘 Guía del Panel de Administrador (Backend Laravel)

> Este documento explica el funcionamiento del panel de control desarrollado en Laravel. Su objetivo principal es permitir a los administradores gestionar el catálogo de la tienda (productos, fotos, stock, etc.) de forma segura, visual y conectada en tiempo real con la base de datos y el Frontend (Angular).

---

## 1. 🛡️ Sistema de Seguridad y Acceso (El "Guardaespaldas")
Para proteger la tienda de intrusos, el panel cuenta con un sistema de autenticación personalizado, independiente del registro normal de clientes.

- **Punto de entrada:** Se accede a través de la ruta web `/admin/login`.
- **Credenciales:** El acceso está restringido a un usuario específico (`adminProductos@gmail.com`).
- **El Controlador (`AuthController`):** Verifica que el correo y la contraseña coincidan. Si es correcto, genera un "ticket VIP" guardando una variable llamada `admin_identificado` en la sesión del navegador.
- **El Middleware (`AdminAuth`):** Es un filtro de seguridad que vigila todas las páginas internas del panel. Si alguien intenta escribir `/admin/productos` en el navegador sin haberse logueado, este filtro le bloquea el paso y le redirige automáticamente a la pantalla de login.

---

## 2. 🛣️ Estructura de Rutas
Las URLs de la aplicación están divididas estratégicamente para separar lo público de lo privado:

- **Rutas Públicas:** Solo incluyen el formulario de login y la acción de cerrar sesión.
- **Rutas Protegidas:** Agrupadas bajo el prefijo `/admin` y protegidas por el Middleware. Aquí dentro vive todo el sistema de gestión de productos.

---

## 3. 📦 Gestión de Productos (CRUD)
El corazón del panel es el `AdminProductoController`, que se encarga de las cuatro operaciones básicas de cualquier base de datos (Crear, Leer, Actualizar, Borrar).

- **Listado (Index):** Muestra una tabla con todos los productos existentes en la tienda.
- **Creación (Create/Store):** Permite rellenar un formulario con el nombre, precio, descripción y stock. Al guardar, el controlador valida que los datos sean correctos (por ejemplo, que el precio sea un número) y los inserta en la base de datos.
- **Edición (Edit/Update):** Recupera los datos de un producto existente y permite modificarlos.
- **Borrado (Destroy):** Elimina el producto de la base de datos para siempre.

---

## 4. 🔗 Relaciones Inteligentes (Marcas, Categorías, Tallas y Colores)
El panel no trabaja con datos aislados, sino que conecta los productos con otras tablas de la base de datos de forma dinámica:

- **Desplegables Dinámicos:** En lugar de escribir números (IDs), los formularios de crear y editar muestran listas desplegables (`<select>`) con los nombres reales de las Marcas y Categorías.
- **Selección Múltiple (Checkboxes):** Para las Tallas y Colores, el panel muestra cajitas marcables.
- **Sincronización (`sync`):** Al guardar un producto, el controlador usa el comando `sync()` de Laravel para vincular automáticamente el producto con las múltiples tallas y colores elegidos, utilizando tablas intermedias en la base de datos.

---

## 5. 🖼️ Gestión de Imágenes y Archivos
El panel está preparado para manejar archivos multimedia reales, no solo texto.

- **Formularios Habilitados:** Los formularios HTML utilizan el atributo `enctype="multipart/form-data"` para permitir la transmisión segura de archivos pesados.
- **Almacenamiento Local/Nube:** Las fotos subidas se guardan físicamente en la carpeta `storage/app/public/productos`. En la base de datos solo se guarda la ruta (el nombre) del archivo.
- **Limpieza Automática:** Cuando un administrador edita un producto y sube una foto nueva, el sistema elimina automáticamente la foto antigua del servidor para ahorrar espacio y mantener el disco limpio.

---

## 6. 🚀 Integración con Producción (AWS y Angular)
El panel está diseñado para funcionar de manera conjunta con la tienda pública y sobrevivir en un servidor en la nube.

- **Sincronización en Tiempo Real:** Como el panel y el Frontend de Angular comparten la misma base de datos, cualquier cambio de stock, precio o foto que se haga desde el panel, aparecerá instantáneamente en la tienda pública sin necesidad de recargar el servidor.
- **Protección de Datos:** Para los despliegues en AWS, se utiliza el comando de migración segura (`php artisan migrate --force`) en lugar del comando destructivo (`migrate:fresh`). Esto garantiza que los productos subidos y las compras realizadas no se borren accidentalmente al actualizar el código.