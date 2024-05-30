FROM php:8.2-apache

COPY docker/php-apache .
COPY . .

EXPOSE 80

# Install system dependencies
RUN apt-get -y update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    curl \
    git \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && apt install nano

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy conf file
COPY ./docker/php-apache/apache2/sites-enabled/001-rci.conf /etc/apache2/sites-available/001-rci.conf

#fix server name
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && a2dissite 000-default \
    && a2ensite 001-rci \
    && service apache2 restart

#Install project dependencies
RUN composer install
