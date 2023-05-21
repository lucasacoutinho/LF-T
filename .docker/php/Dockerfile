FROM php:8.1-fpm-alpine

RUN echo "America/Sao_Paulo" | tee /etc/timezone && \
    ln -fs /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime

RUN apk update && \
    apk add --no-cache $PHPIZE_DEPS\
    curl \
    oniguruma-dev \
    libpq-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    jpegoptim optipng pngquant gifsicle \
    libzip-dev \
    zip \
    unzip \
    git

RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-enable gd && \
    docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

USER www

COPY --chown=www:www . /var/www

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]
