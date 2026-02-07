#!/bin/bash
set -e

echo "=== Container Startup ==="

# 1. Create necessary directories
mkdir -p /run/php /var/run/mysqld
chown -R www-data:www-data /run/php
chown -R mysql:mysql /var/run/mysqld /var/lib/mysql

# 2. Initialize MySQL if not already initialized
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "Initializing MySQL database..."
    mysql_install_db --user=mysql --ldata=/var/lib/mysql
    
    echo "Starting temporary MySQL for setup..."
    mysqld_safe --daemonize --skip-networking --socket=/var/run/mysqld/mysqld.sock &
    
    # Wait for MySQL
    echo "Waiting for MySQL to start..."
    for i in $(seq 1 30); do
        if mysqladmin --socket=/var/run/mysqld/mysqld.sock ping 2>/dev/null; then
            echo "MySQL is ready!"
            break
        fi
        sleep 1
    done
    
    # Create database
    echo "Creating database..."
    mysql -uroot --socket=/var/run/mysqld/mysqld.sock -e "CREATE DATABASE IF NOT EXISTS mini_api;" || true
    
    # Shutdown temporary MySQL
    echo "Shutting down temporary MySQL..."
    mysqladmin -uroot --socket=/var/run/mysqld/mysqld.sock shutdown || true
    sleep 2
fi

# 3. Start MySQL for runtime
echo "Starting MySQL..."
mysqld_safe --daemonize --socket=/var/run/mysqld/mysqld.sock &

# 4. Wait for MySQL to be ready
echo "Waiting for MySQL..."
sleep 5

# 5. Start supervisor
echo "Starting Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf