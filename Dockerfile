FROM php:8.2-apache

# Copy semua file ke folder web server
COPY . /var/www/html/

# (Opsional) Aktifkan mod_rewrite kalau pakai routing
RUN a2enmod rewrite

# (Opsional) Set permission
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80