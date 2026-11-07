FROM php:8.2-fpm-alpine

# Set environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    supervisor \
    dos2unix

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    gd \
    zip \
    opcache \
    bcmath

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies with resilient flags
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs --no-scripts

# Install Node dependencies and compile production assets
RUN npm install && npm run build && rm -rf node_modules

# Ensure required directories exist and set correct permissions
RUN mkdir -p storage/logs \
             storage/framework/sessions \
             storage/framework/views \
             storage/framework/cache/data \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Configure PHP-FPM
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/opcache.ini  /usr/local/etc/php/conf.d/opcache.ini

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Configure Supervisor to manage Nginx + PHP-FPM
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Startup script (convert Windows CRLF to Linux LF)
COPY docker/start.sh /start.sh
RUN dos2unix /start.sh && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
