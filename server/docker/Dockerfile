##########################################################
############ @author Bruno Rezende #######################
##########################################################

FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Dependências básicas para Laravel + PDO + Composer
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    g++ \
    make \
    icu-dev \
    mysql-client \
    && docker-php-ext-install pdo_mysql mbstring zip intl

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
EXPOSE 8000
