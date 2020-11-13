FROM php:7.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
		git \
		curl \
		zip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clear cache
RUN apt-get clean && apt-get autoremove -y && rm -rf /var/lib/apt/lists/* && docker-php-source delete

# Add user for laravel application
#RUN groupadd -g 1000 www
#RUN useradd -u 1000 -ms /bin/bash -g www www
#RUN usermod -aG www-data

# Copy existing application directory contents
#COPY . /var/www

# Copy existing application directory permissions
#COPY --chown=www:www . /var/www

# Change current user to www
#USER www

# Expose port 9000 and start php-fpm server
#EXPOSE 9000
#CMD ["php-fpm"]

# Add user for application
#RUN groupadd -g 1000 admin:admin
#RUN useradd -u 1000 -ms /bin/bash -g admin:admin

# Install composer
#USER root

#RUN curl -sS https://getcomposer.org/installer | php
#RUN mv composer.phar /usr/local/bin/composer

# Change current user to admin
#USER admin:admin

# Expose port 9000 and start php-fpm server
#EXPOSE 9000
#CMD ["php-fpm"]