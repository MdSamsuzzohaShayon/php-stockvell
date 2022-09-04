FROM php:7.4-apache


WORKDIR /var/www/html/

# Required file for php
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install php composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json .
RUN composer update


COPY . .



# User previlages
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R goa+rwx /var/www/html
# CMD [ "php", "./your-script.php" ]
