# Usamos una imagen oficial de PHP. La versión 8.2 es ideal para Laravel 10/11.
FROM php:8.2-cli

# Instalar dependencias del sistema necesarias
# (git, zip para composer, librerías para imágenes y bases de datos)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Limpiar caché de apt para reducir el tamaño de la imagen
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP requeridas por Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer (el gestor de paquetes de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# (Opcional) Instalar Node.js y NPM para la parte visual (Vite/Frontend)
# Esto es necesario porque tienes el puerto 5173 abierto en el docker-compose
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Establecer el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Exponer los puertos (Informativo, Docker Compose ya los mapea)
EXPOSE 8000
EXPOSE 5173

# Comando por defecto al iniciar el contenedor.
# Usamos bash para que el contenedor se mantenga encendido y puedas entrar a ejecutar comandos
CMD ["bash"]