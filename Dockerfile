# Imagem base: PHP 8.2 com Apache embutido
FROM php:8.2-apache

# ─── Dependências do sistema ─────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    && rm -rf /var/lib/apt/lists/*

# ─── Extensões PHP ───────────────────────────────────────────────────────────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        mbstring \
        xml \
        bcmath \
        opcache

# ─── Composer ───────────────────────────────────────────────────────────────
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ─── Habilita mod_rewrite (necessário para URLs amigáveis) ───────────────────
RUN a2enmod rewrite

# ─── Configuração do Apache: permite .htaccess ───────────────────────────────
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

# ─── Ajusta DocumentRoot para a pasta public ─────────────────────────────────
RUN sed -ri 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/src/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!<Directory /var/www/html>!<Directory /var/www/html/src/public>!g' /etc/apache2/apache2.conf

# ─── Configuração recomendada do PHP ─────────────────────────────────────────
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY ./config/php-custom.ini $PHP_INI_DIR/conf.d/custom.ini

# ─── Diretório de trabalho ───────────────────────────────────────────────────
WORKDIR /var/www/html

EXPOSE 80
