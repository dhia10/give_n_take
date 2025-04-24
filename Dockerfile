FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libpq-dev libzip-dev libonig-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose the correct port and run the built-in server
EXPOSE 9000
CMD echo "Starting PHP server on port ${PORT:-9000}" && php -S 0.0.0.0:${PORT:-9000} -t public

