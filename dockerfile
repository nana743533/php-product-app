FROM php:7.4-apache

# 必要な拡張をインストール（pdo_mysql）
RUN docker-php-ext-install pdo pdo_mysql

# ドキュメントルートを設定（任意）
COPY ./doc_root /var/www/html
