FROM php:8.2-apache

# 啟用 Apache Rewrite 模組（Laravel 需要）
RUN a2enmod rewrite

# 安裝系統工具與 PHP 擴充
RUN apt-get update \
    && apt-get install -y git unzip zip curl libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# 安裝 Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 設定 Laravel 專案路徑
WORKDIR /var/www/html

RUN ls -la
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN cat /etc/apache2/sites-available/000-default.conf

