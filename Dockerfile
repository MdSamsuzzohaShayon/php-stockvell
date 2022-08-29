FROM php:7.4-apache


WORKDIR /var/www/html/

COPY . /var/www/html/

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R goa+rwx /var/www/html
# CMD [ "php", "./your-script.php" ]
