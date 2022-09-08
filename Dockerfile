FROM php:7.4-apache


WORKDIR /var/www/html/

# User previlages
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R goa+rwx /var/www/html
# CMD [ "php", "./your-script.php" ]

# Required file for php
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install php composer
RUN curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer 

COPY composer.json .


COPY . .




