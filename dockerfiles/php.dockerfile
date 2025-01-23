FROM php:8.3.11-fpm

# Install required dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libzip-dev \
    libjpeg-dev \
    libwebp-dev \
    libxpm-dev \
    libfreetype6-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    bash \
    fcgiwrap \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
    gd \
    pdo \
    pdo_pgsql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    opcache


# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Copy application files
COPY . /var/www/html/

# Set correct ownership for application files
RUN chown -R www-data:www-data /var/www/html/

# Switch to www-data user
USER www-data

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
