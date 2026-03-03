# 🚀 Guía de Despliegue Frontend (Angular) en AWS

Esta guía detalla cómo automatizar el despliegue del Frontend mediante GitHub Actions hacia nuestra instancia EC2 de Ubuntu en AWS, conectándose con el Backend de OutfitGo.

## ⚙️ Archivo de Configuración de GitHub Actions

Crea el siguiente archivo en el repositorio de tu Frontend en la ruta `.github/workflows/deploy.yml`:

```yaml
name: Deploy Angular Frontend to AWS EC2

on:
  push:
    branches: [ "main" ] # O la rama principal de tu repositorio Frontend

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout Code
        uses: actions/checkout@v4

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: 20 # Ajusta a la versión de Node de tu proyecto Angular
          cache: 'npm'

      - name: Install Dependencies
        run: npm ci # 'npm ci' es más seguro/rápido para CI que 'npm install'

      - name: Build Angular Project
        run: npm run build --configuration=production

      - name: Copy to AWS via SCP
        uses: appleboy/scp-action@v0.1.7
        with:
          host: ${{ secrets.SERVER_IP }}
          username: ${{ secrets.SERVER_USER }}
          key: ${{ secrets.SERVER_SSH_KEY }}
          # ⚠️ IMPORTANTE: 'dist/outfit-go-frontend' debe coincidir con el 'outputPath' de tu angular.json
          source: "dist/outfit-go-frontend/browser/*"
          target: "/var/www/html/frontend"
          strip_components: 3 # Elimina la jerarquía de carpetas dist/... al copiar para que los archivos queden en la raíz destino
```

### ✅ Secretos Necesarios en tu Repositorio de GitHub
Para que el workflow anterior funcione, debes ir a **Settings > Secrets and variables > Actions** en tu repositorio Frontend de GitHub y configurar estos 3 secretos:
*   `SERVER_IP`: La IP de nuestra instancia en AWS (`34.229.141.169`).
*   `SERVER_USER`: Tu usuario de SSH (ej. `ubuntu`).
*   `SERVER_SSH_KEY`: La clave privada `.pem` completa (con todo y las cabeceras `-----BEGIN RSA PRIVATE KEY-----`).

---

## 📝 Actualización Necesaria para el README.md del Frontend

Como estipula la arquitectura del proyecto y las reglas del **Core Engine v2.0**, por favor **copia y pega** el siguiente bloque de documentación en el archivo `README.md` de tu proyecto Angular.

```markdown
## ⚠️ Reglas Core de Negocio: Consumo de Productos

Este Frontend interactúa directamente con la API RESTful del Backend OutfitGo. Para mantener un diseño consistente y sin cortes visuales en nuestras *cards* o vistas de detalles, **es estrictamente obligatorio**:

1. **Procedencia de Textos**: Absolutamente todas las descripciones de los productos mostradas en pantalla DEBEN provenir de la lectura directa del campo `descripcion` proporcionado por el JSON del Backend. No se permite la manipulación directa de contenidos de producto (mocking) en vistas de producción.
2. **Longitud Asegurada**: El Backend nos garantiza por sus Factory Builders y validaciones que **toda descripción tiene una longitud estricta de entre 300 y 500 caracteres**. 
   *    Debes diseñar las interfaces previendo siempre textos extensos de al menos 300 caracteres.
   *    Si una tarjeta de producto es pequeña, *debes implementar utilidades como "text-truncate" (CSS `line-clamp`), un botón de "Leer Más" o un comportamiento expansivo.* 
```
