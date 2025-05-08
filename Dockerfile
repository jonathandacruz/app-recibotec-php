FROM php:8.2-fpm

# Instala dependências de sistema e extensões do PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Copia os arquivos da aplicação (serão sobrescritos pelo volume, mas úteis em produção)
COPY . /var/www

# Ajusta permissões (útil para evitar problemas com storage/framework/cache, logs, etc.)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Expondo a porta padrão do PHP-FPM (opcional)
EXPOSE 9000
