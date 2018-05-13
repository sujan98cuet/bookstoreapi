FROM docker.io/codereviewvideos/php-7:latest

WORKDIR /testbookstore

COPY Makefile composer.json composer.lock ./

COPY bin ./bin
COPY src ./src
COPY config ./config
COPY public ./public

COPY .env.dist /.env

ENV COMPOSER_ALLOW_SUPER_USER 1

RUN cd /testbookstore && make check-composer
RUN cd /testbookstore && composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts

EXPOSE 8080

