FROM php:8.2-apache

# Mengaktifkan ekstensi mysqli dan pdo_mysql untuk koneksi database
RUN docker-php-ext-install mysqli pdo_mysql

# Menonaktifkan semua MPM yang bentrok dan hanya mengaktifkan prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
    && rm -f /etc/apache2/mods-enabled/mpm_event.conf \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.load \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.conf \
    && a2enmod mpm_prefork

# Menyalin seluruh file project ke direktori web server
COPY . /var/www/html/

# Mengatur hak akses folder web
RUN chown -R www-data:www-data /var/www/html