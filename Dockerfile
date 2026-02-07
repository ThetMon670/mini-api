# 1. Base image
FROM ubuntu:24.04

# 2. Disable interactive prompts
ENV DEBIAN_FRONTEND=noninteractive

# 3. Update system & install base tools
RUN apt-get update && apt-get install -y \
    software-properties-common \
    openssl \
    vim \
    curl \
    wget \
    unzip \
    git \
    supervisor \
    nginx \
    sqlite3 \
    mariadb-server \
    mariadb-client

# 4. Add PHP repository for PHP 8.4
RUN add-apt-repository ppa:ondrej/php -y

# 5. Install PHP 8.4 + Laravel extensions
RUN apt-get update && apt-get install -y \
    php8.4 \
    php8.4-cli \
    php8.4-fpm \
    php8.4-mysql \
    php8.4-sqlite3 \
    php8.4-gd \
    php8.4-xml \
    php8.4-mbstring \
    php8.4-curl \
    php8.4-zip \
    php8.4-bcmath \
    php8.4-redis

# 6. Install Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# 7. PHP upload settings (CLI + FPM)
RUN for version in 8.4; do \
        if [ -f "/etc/php/$version/cli/php.ini" ]; then \
            sed -i 's/upload_max_filesize = .*/upload_max_filesize = 1000M/' /etc/php/$version/cli/php.ini; \
            sed -i 's/post_max_size = .*/post_max_size = 1000M/' /etc/php/$version/cli/php.ini; \
        fi; \
        if [ -f "/etc/php/$version/fpm/php.ini" ]; then \
            sed -i 's/upload_max_filesize = .*/upload_max_filesize = 1000M/' /etc/php/$version/fpm/php.ini; \
            sed -i 's/post_max_size = .*/post_max_size = 1000M/' /etc/php/$version/fpm/php.ini; \
        fi; \
    done

# 8. Fix PHP-FPM socket directory
RUN mkdir -p /run/php && chown -R www-data:www-data /run/php

# 9. Set working directory
WORKDIR /var/www

# 10. Copy project files
COPY . .

# 11. Install PHP dependencies
RUN composer install --no-interaction --prefer-dist

# 12. Set permissions for Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# 13. Copy Nginx & Supervisor configs
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/site.conf /etc/nginx/sites-available/default
COPY docker/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# 14. Create entrypoint script for MySQL initialization
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 15. Expose HTTP port
EXPOSE 80

# 16. Use entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]