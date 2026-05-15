FROM php:8.2-apache

# 必要な拡張・ツールをインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpdo-mysql-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer インストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache の mod_rewrite を有効化（Laminas MVC のルーティングに必要）
RUN a2enmod rewrite

# Apache ドキュメントルートを Laminas の public/ に設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

# .htaccess を有効にするため AllowOverride を All に変更
RUN sed -ri 's|AllowOverride None|AllowOverride All|g' \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html