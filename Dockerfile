FROM dunglas/frankenphp:1-php8.4

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    bcmath \
    opcache \
    pcntl \
    redis

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]

CMD ["--host=0.0.0.0", "--port=8000"]
