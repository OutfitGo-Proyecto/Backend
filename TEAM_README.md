# 🚀 OutfitGo - Proyecto de Desarrollo Web

> **Tienda Online Inteligente de Moda**

Este documento describe la visión, arquitectura y estructura del equipo para el desarrollo de **OutfitGo**.

## 🎯 Objetivo del Proyecto
Desarrollar una aplicación web moderna (SPA) que permita a los usuarios buscar, filtrar y comprar ropa y accesorios de ropa de nuestras marcas asociadas.

*   **Propósito**: Facilitar la compra de moda en una única plataforma directa.
*   **Foco**: Excelente experiencia de usuario, catálogo extenso y filtros avanzados.

## 🛠️ Stack Tecnológico (Arquitectura Headless)

El proyecto sigue una arquitectura **desacoplada CI/CD** para permitir el trabajo independiente de los equipos de Frontend y Backend.

### 🎨 Frontend (Equipo A)
*   **Framework**: Angular 19.
*   **Enfoque**: Componentes Standalone, Señales (Signals) para reactividad.
*   **Diseño**: UX/UI moderna y responsiva.

### ⚙️ Backend (Equipo B - Nosotros)
*   **Framework**: Laravel 11 (API REST).
*   **Base de Datos**: MySQL 8.0.
*   **Infraestructura**: Docker & Docker Compose.
*   **Testing**: PHPUnit con integración en GitHub Actions.

## 👥 Estructura del Equipo Backend

Nuestro equipo se encarga de la lógica de negocio, gestión de datos y seguridad.

**Responsabilidades Principales:**
1.  **API RESTful**: Proveer endpoints JSON estructurados para el Frontend.
2.  **Base de Datos**: Diseño del esquema Relacional (Productos, Variantes).
3.  **Lógica de Filtrado**: Implementación de filtros avanzados (Talla, Color, Marca, Precio) utilizando relaciones Eloquent optimizadas.
4.  **Calidad**: Mantenimiento de tests automatizados para asegurar la integridad del sistema.

## 🔄 Flujo de Trabajo (Workflow)

1.  **Ramas**: Trabajamos con `main` protegida y ramas de características (`feature/filtro-tallas`, `fix/login`).
2.  **Pull Requests**: Todo código debe pasar por PR y ser validado por los tests de GitHub Actions antes de fusionarse.
3.  **Estándares**:
    *   Código en **Español** para dominio (Modelos: `Talla`, `Color`).
    *   Respuestas API en formato **JSON** estandarizado.

## 📅 Roadmap Actual (Backend)

*   [x] Configuración Inicial (Docker, Laravel).
*   [x] Diseño de Base de Datos (Migraciones).
*   [x] Implementación de Filtros Avanzados (Controller).
*   [ ] Autenticación de Usuarios (Sanctum/JWT).
*   [ ] Integración de pasarelas de pago (Stripe/PayPal) (Futuro).

---
*Documento generado para la coordinación del equipo de desarrollo OutfitGo.*
