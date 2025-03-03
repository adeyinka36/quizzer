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

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Optional: Verify that the Redis extension is installed by listing modules
RUN php -m | grep redis

# Configure PHP upload limits
RUN echo "upload_max_filesize = 12M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 12M" >> /usr/local/etc/php/conf.d/uploads.ini

# Install Composer from the composer image
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

WORKDIR /var/www/

# Copy application files
COPY . /var/www/

# Set proper permissions before switching user
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Configure Git to trust the directory
RUN git config --global --add safe.directory /var/www

# Switch to www-data user and install composer dependencies
USER www-data
RUN composer install --optimize-autoloader --no-dev

EXPOSE 9000

CMD ["php-fpm"]
