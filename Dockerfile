# ==========================================
# Stage 1: Build Laravel Application
# ==========================================
FROM php:8.2-fpm-alpine AS php-build

RUN apk add --no-cache \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev

RUN docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        zip \
        mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

COPY . .

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache


# ==========================================
# Stage 2: Runtime Image
# ==========================================
FROM nginx:alpine

RUN apk add --no-cache \
    php82 \
    php82-fpm \
    php82-opcache \
    php82-pdo_mysql \
    php82-mbstring \
    php82-zip \
    php82-gd \
    php82-xml

WORKDIR /var/www/html

COPY --from=php-build /var/www/html /var/www/html

COPY nginx.config /etc/nginx/conf.d/default.conf
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]