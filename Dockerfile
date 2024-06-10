FROM php:8.2.12-apache


COPY FileMakerORM/ /var/www/html/
COPY ./custom-configs/custom-apache.conf /etc/apache2/sites-available/custom-apache.conf


#define servername as localhost
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


RUN apt-get update && apt-get install -y sudo
# Install libcurl development files
RUN apt-get update && \
    apt-get install -y libcurl4-openssl-dev

# Install libicu development files
RUN apt-get install -y libicu-dev

# Install libonig development files
RUN apt-get install -y libonig-dev

# Install libsqlite3 development files
RUN apt-get install -y libsqlite3-dev


# Install necessary PHP extensions
RUN docker-php-ext-install curl
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install gettext
RUN docker-php-ext-install intl
RUN docker-php-ext-install exif
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_sqlite


RUN sudo a2dissite 000-default.conf
RUN sudo a2ensite custom-apache.conf
RUN sudo a2enmod rewrite 
EXPOSE 80
