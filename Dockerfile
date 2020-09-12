FROM php:7.4-cli

# Install extentions
RUN apt-get update -y && apt-get install -y libzip-dev
RUN apt-get update -y && apt-get install -y libpng-dev
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev
RUN docker-php-ext-install gd
RUN docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

CMD bash -c "composer install && while true; do sleep 1; done"