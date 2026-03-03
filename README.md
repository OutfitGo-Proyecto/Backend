# 🚀 E-Commerce Backend v2.0 | Core Engine

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![AWS](https://img.shields.io/badge/AWS-232F3E?style=for-the-badge&logo=amazon-aws&logoColor=white)
![GitHub Actions](https://img.shields.io/badge/CI/CD-GitHub_Actions-2088FF?style=for-the-badge&logo=github-actions&logoColor=white)

Este proyecto ha evolucionado de un agregador de precios a un **sistema de E-commerce directo** altamente escalable, optimizado para una integración fluida con Angular y desplegado en la nube de AWS.

## 🛠️ Especificaciones Técnicas Actuales

| Característica | Estado Actual |
| :--- | :--- |
| **Modelo de Negocio** | **E-Commerce Directo**. Productos con precio y stock propio. |
| **Segmentación** | **Filtro por Público**: Adulto, Infantil, Unisex (vía Enum). |
| **Calidad de Contenido** | **Descripciones Estrictas**: Longitud controlada entre 300 y 500 caracteres. |
| **Optimización API** | **Eager Loading**: Carga eficiente de Categoría, Tallas y Colores en un solo request. |
| **Infraestructura** | **Cloud Native**: Contenedores Docker en Amazon EC2 con CI/CD automatizado. |

---

## 🏗️ Arquitectura de Datos

El sistema utiliza una estructura relacional optimizada para ropa y calzado, permitiendo una gestión precisa de variantes:

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

### Endpoints Principales
- `GET /api/productos`: Listado paginado (12 items) con carga de relaciones.
- `GET /api/productos?publico=infantil`: Filtrado segmentado por audiencia.
- `POST /api/productos`: Gestión de inventario (restringido).

### Parámetros de Búsqueda para `GET /api/productos`
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
| `publico` | Filtrar por audiencia objetivo. | `?publico=infantil` |

**Ejemplo de respuesta JSON:**
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "nombre": "Zapatillas Runner",
            "slug": "zapatillas-runner",
            "precio": 99.99,
            "stock": 10,
            "marca": { "id": 2, "nombre": "Adidas" },
            "tallas": [ { "nombre": "42" }, { "nombre": "43" } ],
            "colores": [ { "nombre": "Negro", "hex_code": "#000000" } ]
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

## 🚀 Despliegue y Mantenimiento

### Actualización de Base de Datos
Para resetear el entorno y poblarlo con datos de prueba coherentes (Seeders actualizados):
```bash
docker exec laravel php artisan migrate:fresh --seed
```
