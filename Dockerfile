ARG PHP_EXTENSIONS="apcu bcmath pdo_mysql gd"
FROM thecodingmachine/php:7.4-v3-slim-apache AS base
MAINTAINER Youssef SAYYOURI <youssef.sayyouri@gmail.com>
ENV APACHE_DOCUMENT_ROOT=public/

FROM base AS dev
ENV PHP_EXTENSION_XDEBUG=1

FROM base AS prod
ENV TEMPLATE_PHP_INI=production
COPY --chown=docker:docker . /var/www/html
RUN composer install --optimize-autoloader --no-dev
