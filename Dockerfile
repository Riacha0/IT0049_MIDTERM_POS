FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y libicu-dev libonig-dev unzip \
    && docker-php-ext-install intl mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && chmod -R 777 writable

EXPOSE 10000

CMD ["sh", "-c", "php spark serve --host 0.0.0.0 --port ${PORT:-10000}"]