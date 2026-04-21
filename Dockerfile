FROM php:8.2-cli

# Install GD dan extension lain
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy semua file
COPY . .

# Install dependencies (ignore platform req untuk safety)
RUN composer install --optimize-autoloader --no-interaction --ignore-platform-req=ext-gd

# Setup Laravel
# RUN php artisan key:generate
#RUN php artisan config:cache
#RUN php artisan route:cache
#RUN php artisan view:cache

# Permission
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Buat .env dari environment variable Railway
CMD ["sh", "-c", "echo 'APP_NAME=SIMORGAN' > .env && echo 'APP_ENV=production' >> .env && echo 'APP_DEBUG=false' >> .env && echo 'APP_KEY=' >> .env && php artisan key:generate --force && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]