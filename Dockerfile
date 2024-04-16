FROM php:8.2-apache

COPY docker/php-apache .
COPY . .

RUN chmod -R 744 logs
RUN chown -R www-data:www-data logs
RUN chmod -R 744 tmp
RUN chown -R www-data:www-data tmp

EXPOSE 80

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get -y update \
&& apt-get install -y libicu-dev \
    && apt-get install -y libzip-dev

RUN #pecl install xdebug \
#    && docker-php-ext-enable xdebug

RUN docker-php-ext-configure intl

RUN docker-php-ext-install intl

RUN docker-php-ext-install zip

RUN apt install nano

COPY ./docker/php-apache/apache2/sites-enabled/001-rci.conf /etc/apache2/sites-available/001-rci.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && a2dissite 000-default \
    && a2ensite 001-rci \
    && service apache2 restart

#RUN ../vendor/bin/phinx migrate -e development
