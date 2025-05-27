# Resmi PHP image'ı kullan
FROM php:8.2-apache

# Gerekli PHP eklentilerini kur
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer yükle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Apache için rewrite modülünü aktif et
RUN a2enmod rewrite

# Laravel dosyalarını kopyala
COPY . /var/www/html

# Çalışma dizinini ayarla
WORKDIR /var/www/html

# Laravel klasör izinlerini ayarla
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Composer ile bağımlılıkları yükle
RUN composer install --no-dev --optimize-autoloader

# Laravel için APP_KEY üret
RUN php artisan key:generate

# Apache sunucusu başlatıldığında kullanılacak
CMD ["apache2-foreground"]
