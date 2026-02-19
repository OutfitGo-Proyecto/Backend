# Backend - OutfitGo

Este repositorio contiene el backend de la aplicación de comercio electrónico **OutfitGo**, desarrollado con **Laravel 11** y **Docker**. Proporciona una API REST para gestionar productos, marcas, tiendas y filtros avanzados para el frontend en Angular.

## 🚀 Características

*   **API RESTful**: Endpoints para consumo desde frontend.
*   **Filtrado Avanzado**: Productos por Nombre, Marca, Categoría, Talla, Color y Precio.
*   **Dockerizado**: Entorno de desarrollo completo con Docker Compose (Nginx/Apache, MySQL, PHPMyAdmin).
*   **CI/CD**: Integración continua con GitHub Actions para ejecutar tests automáticamente.

## 📋 Requisitos Previos

*   [Docker Desktop](https://www.docker.com/products/docker-desktop) instalado y ejecutándose.
*   Git.

## 🛠️ Instalación y Puesta en Marcha

1.  **Clonar el repositorio**:
    ```bash
    git clone https://github.com/tu-usuario/Backend-OutfitGo.git
    cd Backend-OutfitGo
    ```

2.  **Iniciar los contenedores Docker**:
    Levanta el entorno de base de datos y aplicación.
    ```bash
    docker compose up -d
    ```

3.  **Configuración Inicial (Solo la primera vez)**:
    Instala dependencias y configura la base de datos dentro del contenedor `laravel`.
    ```bash
    # Instalar dependencias de PHP
    docker exec laravel composer install

    # Copiar archivo de entorno y generar clave
    docker exec laravel php -r "file_exists('.env') || copy('.env.example', '.env');"
    docker exec laravel php artisan key:generate

    # Ejecutar migraciones (crea tablas de productos, tallas, colores, etc.)
    docker exec laravel php artisan migrate
    ```

4.  **Verificar estado**:
    Accede a `http://localhost:8000` para ver la página de bienvenida de Laravel.

## 📡 Documentación de la API

La API principal para el catálogo se encuentra en `/api/productos`.

### `GET /api/productos`
Obtiene la lista paginada de productos. Soporta los siguientes parámetros de consulta (query params):

| Parámetro | Descripción | Ejemplo |
| :--- | :--- | :--- |
| `q` | Búsqueda por texto (nombre o descripción). | `?q=Nike` |
| `marca_id` | ID de la marca. | `?marca_id=1` |
| `categoria_id` | ID de la categoría. | `?categoria_id=5` |
| `talla` | Filtrar por nombres de talla (separados por coma). | `?talla=M,L` |
| `color` | Filtrar por nombres de color (separados por coma). | `?color=Rojo,Azul` |
| `precio_min` | Precio mínimo. | `?precio_min=50` |
| `precio_max` | Precio máximo. | `?precio_max=150` |

**Ejemplo de respuesta JSON:**
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "nombre": "Zapatillas Runner",
            "slug": "zapatillas-runner",
            "marca": { "id": 2, "nombre": "Adidas" },
            "tallas": [ { "nombre": "42" }, { "nombre": "43" } ],
            "colores": [ { "nombre": "Negro", "hex_code": "#000000" } ],
            "tiendas": [
                { "nombre": "Zalando", "pivot": { "precio": 99.99, "url": "..." } }
            ]
        }
    ],
    "total": 50
}
```

### `GET /api/productos/{slug}`
Obtiene el detalle de un producto específico por su URL amigable (slug).

## ✅ Tests

El proyecto incluye tests automatizados (PHPUnit) que se ejecutan en cada Push mediante GitHub Actions.

Para ejecutar los tests localmente:
```bash
docker exec laravel php artisan test
```

## 📂 Estructura del Proyecto

*   `home/`: Código fuente de Laravel.
*   `docker-compose.yaml`: Configuración de servicios Docker.
*   `.github/workflows/`: Configuración de CI/CD.
