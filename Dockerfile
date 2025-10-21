# Base image: PHP 8.3-FPM with Alpine for a smaller footprint
FROM php:8.3-fpm-alpine

# Arguments for the app's user/group ID (important for file permissions)
ARG UID=1000
ARG GID=1000

# Install necessary system dependencies and PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    bash \
    make \
    unzip \
    openssl
#    supervisor

RUN apk add --no-cache nodejs npm

RUN apk add icu-dev


# Install PHP extensions required by Laravel and common tools
# RUN docker-php-ext-install opcache
# RUN docker-php-ext-configure gd --with-jpeg --with-webp
# RUN docker-php-ext-install gd

# Clear cache and temporary files
RUN rm -rf /var/cache/apk/*

# Install Composer globally
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory for the application
WORKDIR /var/www/html

# Expose port 9000 for Nginx FastCGI
EXPOSE 9000


