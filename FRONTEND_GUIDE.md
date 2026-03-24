# 📘 Guía de Integración Frontend (Angular) -> Backend E-Commerce v2.0

Bienvenido al Backend de OutfitGo (Core Engine v2.0). Esta guía está diseñada para proporcionar al equipo de Frontend (Angular) toda la información necesaria para consumir nuestra API RESTful basada en Laravel 11. 

## 🌐 Configuración Base

*   **URL Base de la API**: `http://52.4.105.78/api`
*   **CORS**: Actualmente configurado con `allowed_origins => ['*']`. Puedes hacer peticiones desde tu entorno local (`localhost:4200`) o staging sin enfrentarte a problemas de preflight (bloqueos CORS).
*   **Autenticación**: Por el momento, el catálogo de productos es **Público**. No se requiere token de Sanctum/JWT ni cabeceras de `Authorization` para consumir los endpoints de lectura (`GET /api/productos`).

---

## 🏗️ Estructura de Datos (E-Commerce Directo)

El modelo de negocio ha cambiado. **Ya no somos un agregador ni comparador de precios**. Por lo tanto:
*   El concepto de `Tiendas` y `ProductoTienda` ha desaparecido.
*   **Cada producto es nuestro**: Esto significa que los campos `precio` (decimal) y `stock` (integer) vienen **directamente en la raíz del objeto del producto**, facilitando mucho el renderizado en las tarjetas del Frontend.

### Segmentación de Ropa (Campo Público)
Para una mejor navegación, el catálogo de ropa ahora está segmentado.
*   **Campo**: `publico`
*   **Tipo**: Enum
*   **Valores Posibles**: `"adulto"`, `"infantil"`, `"unisex"`

---

## 🛠️ Endpoints y Eager Loading

El endpoint principal para obtener la data es:

`GET /api/productos`

Este endpoint devuelve resultados paginados e incluye **Eager Loading**. Esto significa que en una sola petición obtendrás el producto y todas sus relaciones ya resueltas (sin tener que hacer peticiones extra por ID).

### Relaciones Incluidas por defecto:
*   `categoria` (Objeto)
*   `marca` (Objeto)
*   `tallas` (Array de Objetos con tabla pivote subyacente)
*   `colores` (Array de Objetos con su código hexadecimal)

### Parámetros de Filtro Soportados (Query Params)

Puedes concatenar cualquiera de estos parámetros en la URL:

| Parámetro | Ejemplo | Descripción |
| :--- | :--- | :--- |
| `publico` | `?publico=infantil` | Filtra el catálogo por el segmento de edad (adulto, infantil, unisex). |
| `q` | `?q=zapatillas` | Búsqueda por texto en el nombre, descripción o marca. |
| `marca_id` | `?marca_id=2` | Retorna solo productos pertenecientes a esa marca. |
| `categoria_id` | `?categoria_id=1` | Retorna solo productos de esa categoría específica. |
| `talla` | `?talla=M,L` | Muestra productos que ofrezcan *al menos* una de las tallas listadas. |
| `color` | `?color=Azul` | Filtra por nombre exacto del color. |
| `precio_min` | `?precio_min=20` | Acota por precio base del producto. |
| `precio_max` | `?precio_max=100` | Acota por precio base del producto. |

---

## 📝 Reglas de Negocio a tener en cuenta en la UI

1.  **Descripciones Largas**: La regla de negocio estipula que `descripcion` **siempre** será un bloque de texto que contiene estrictamente entre **300 y 500 caracteres**. Tenlo en cuenta para tu maquetación (uso de `text-ellipsis`, botones de "leer más", o límites de altura en las _cards_).
2.  **Lógica Infantil / Adulto**: Los productos infantiles traen tallaje de edad (ej. "4Y", "6Y"), mientras que los adultos traen clásico ("S", "M") o numérico de calzado. Tu UI debe renderizar lo que venga en el array `tallas`, ya está coordinado desde backend.

---

## 🚀 Repositorio y CI/CD

El backend implementa un pipeline de integracion continua (GitHub Actions). 
*   Cualquier _Push_ a la rama `main` **despliega automáticamente el código al servidor AWS EC2** y reinicia los contenedores.
*   Si notas fallos tras un despliegue, repórtalo, pero ten en cuenta que los cambios en Back se reflejarán instantáneamente en la URL de staging.

---

## 📄 Ejemplo JSON de Respuesta Real

A continuación, un ejemplo exacto de lo que obtendrás al llamar a la API para un producto *infantil*:

```json
{
    "id": 1,
    "marca_id": 1,
    "categoria_id": 2,
    "nombre": "Fugit eum sed blanditiis.",
    "slug": "non-ullam-omnis-temporibus-laudantium",
    "descripcion": "Así lo hice, venciendo los halagos de Doña Flora... (300 a 500 caracteres asegurados)",
    "publico": "infantil",
    "url_imagen_principal": "https://via.placeholder.com/640x480.png/00cc99?text=fashion+Product",
    "precio": "161.90",
    "stock": 25,
    "created_at": "2026-03-03T12:44:58.000000Z",
    "updated_at": "2026-03-03T12:44:58.000000Z",
    "marca": {
        "id": 1,
        "nombre": "Nike",
        "slug": "nike",
        "url_logo": "...",
        "created_at": "2026-03-03T12:44:57.000000Z",
        "updated_at": "2026-03-03T12:44:57.000000Z"
    },
    "categoria": {
        "id": 2,
        "nombre": "Sudaderas",
        "slug": "sudaderas",
        "categoria_padre_id": null,
        "created_at": "2026-03-03T12:44:57.000000Z",
        "updated_at": "2026-03-03T12:44:57.000000Z"
    },
    "tallas": [
        {
            "id": 5,
            "nombre": "4Y",
            "created_at": "2026-03-03T12:44:57.000000Z",
            "updated_at": "2026-03-03T12:44:57.000000Z",
            "pivot": {
                "producto_id": 1,
                "talla_id": 5
            }
        },
        {
            "id": 8,
            "nombre": "10Y",
            "created_at": "2026-03-03T12:44:57.000000Z",
            "updated_at": "2026-03-03T12:44:57.000000Z",
            "pivot": {
                "producto_id": 1,
                "talla_id": 8
            }
        }
    ],
    "colores": [
        {
            "id": 2,
            "nombre": "Blanco",
            "hex_code": "#FFFFFF",
            "created_at": "2026-03-03T12:44:57.000000Z",
            "updated_at": "2026-03-03T12:44:57.000000Z",
            "pivot": {
                "producto_id": 1,
                "color_id": 2
            }
        }
    ]
}
```
