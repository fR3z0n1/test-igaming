FROM php:8.0-fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

## Add user for laravel application
#RUN groupadd -g 1000 www
#RUN useradd -u 1000 -ms /bin/bash -g www www
# Copy existing application directory contents
#COPY . /var/www
# Copy existing application directory permissions
#COPY --chown=www:www . /var/www/html
# Change current user to www
#USER www
# Expose port 9000 and start php-fpm server
#EXPOSE 9000
CMD ["php-fpm"]