FROM php:8.3-fpm-alpine3.19

LABEL maintainer="Vitaly Aksonov"

ARG GID
ARG UID

ENV UID=${UID}
ENV GID=${GID}
ENV COMPOSER_HOME=/tmp/composer
ENV APPLICATION_DIRECTORY=/app

# Install system dependencies
RUN apk update && \
    apk add --update --no-cache git libzip-dev libpng-dev libpq-dev linux-headers && \
    apk add --no-cache $PHPIZE_DEPS --virtual php-ext-deps && \
    apk add --no-cache bash git && \
    apk add nodejs npm

# Install PHP extensions
RUN apk add --no-cache $PHPIZE_DEPS --virtual php-ext-deps && \
        apk add --no-cache icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev && \
        apk add --no-cache libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev imap-dev && \
        docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
        docker-php-ext-install pdo_pgsql && \
        docker-php-ext-install gd && \
        docker-php-ext-install imap && \
        docker-php-ext-install zip && \
        docker-php-ext-install bcmath && \
        docker-php-ext-configure intl && \
        docker-php-ext-install intl && \
        pecl install redis && \
        docker-php-ext-enable redis && \
        docker-php-ext-configure opcache --enable-opcache && \
        docker-php-ext-install opcache && \
        pecl install xdebug && \
        apk del php-ext-deps

RUN mkdir -p -m 0777 $APPLICATION_DIRECTORY \
        && mkdir -p -m 0777 $COMPOSER_HOME /tmp/nginx-public

# Get Composer
COPY --from=composer:2.7.6 /usr/bin/composer /usr/bin/composer

ENV TZ=UTC
RUN ln -s /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Set working directory
WORKDIR $APPLICATION_DIRECTORY

RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# User
USER laravel

ENTRYPOINT ["./entrypoint.sh"]
CMD ["php-fpm"]

EXPOSE 9000
