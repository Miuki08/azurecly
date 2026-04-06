# build PHP dependencies
FROM php:8.2-fpm-alpine AS php-build

# Install system dependencies
RUN apk add --no-cache \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev \
    curl

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo pdo_mysql gd zip mbstring

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy the rest of the app
COPY . .

# Optimize Laravel
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Stage 2: nginx + php-fpm
FROM nginx:alpine

# Install PHP-FPM
RUN apk add --no-cache php82 php82-fpm php82-opcache php82-pdo_mysql php82-mbstring php82-zip php82-gd php82-xml

# Copy source from build stage
COPY --from=php-build /var/www/html /var/www/html

WORKDIR /var/www/html

# Nginx config
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

CMD ["sh", "-c", "php-fpm82 -D && nginx -g 'daemon off;'"]