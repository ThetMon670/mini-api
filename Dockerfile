FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer manually
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer

WORKDIR /var/www

COPY . .

# Temporarily modify providers.php to remove Telescope reference before installation
RUN if [ -f bootstrap/providers.php ]; then \
        cp bootstrap/providers.php bootstrap/providers.php.backup && \
        sed -i '/TelescopeServiceProvider/d' bootstrap/providers.php; \
    fi

# Install only production dependencies (Telescope will be removed)
RUN composer install --no-dev --optimize-autoloader

# Restore the original providers.php if needed (or keep modified version)
# RUN if [ -f bootstrap/providers.php.backup ]; then \
#         mv bootstrap/providers.php.backup bootstrap/providers.php; \
#     fi

RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]