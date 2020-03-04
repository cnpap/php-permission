FROM php:7.4-fpm

COPY aliyun.list /etc/apt/sources.list

RUN apt update \
&& apt install -y libpq-dev \
&& docker-php-ext-install pdo_pgsql