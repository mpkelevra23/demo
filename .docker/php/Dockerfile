FROM php:7.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
		curl \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_pgsql

# Clear cache
RUN apt-get clean && apt-get autoremove -y && rm -rf /var/lib/apt/lists/* && docker-php-source delete

# Add user for application
RUN groupadd -g 1000 www-data
RUN useradd -u 1000 -ms /bin/bash -g www-data:www-data

# Install composer
USER root

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# Change current user to www-data
USER www-data:www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]