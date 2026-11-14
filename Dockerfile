FROM php:8.2-apache

# Mengaktifkan ekstensi mysqli dan pdo_mysql yang diperlukan
RUN docker-php-ext-install mysqli pdo_mysql

# Memperbaiki konflik MPM Apache di image PHP Debian
RUN a2dismod mpm_event mpm_worker && a2enmod mpm_prefork

# Menyalin seluruh file project ke direktori web server Apache
COPY . /var/www/html/

# Mengizinkan hak akses folder
RUN chown -R www-data:www-data /var/www/html