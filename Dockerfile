FROM php:7.1.20-apache

RUN apt-get -y update --fix-missing
RUN apt-get upgrade -y

# Debug utilities
RUN apt-get -y install nano

# Enable apache modules
RUN a2enmod rewrite headers

# Copy existing code base into the container
COPY ./www /var/www/html
COPY ./vhosts /etc/apache2/sites-enabled

EXPOSE 80
