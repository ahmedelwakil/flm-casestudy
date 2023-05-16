FROM php:7.4-apache

ENV ACCEPT_EULA=Y

#Install zip, gd and bcmath
RUN apt-get update -y && apt-get install -y git wget libpng-dev zlib1g-dev libzip-dev libssl-dev pkg-config libpq-dev libpng-dev libzip-dev zlib1g-dev
RUN docker-php-ext-install zip gd bcmath

#Install DB ext
RUN docker-php-ext-install pdo pdo_mysql

#install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

#Copy SC for prod only
WORKDIR /var/www/html/
COPY . .

#Run composer for prod only
RUN set -eux; \
#composer install
	composer install --no-dev --prefer-dist --no-scripts --no-progress --no-suggest; \
	composer clear-cache

#Copy Apache Conf and enable rewrite and headers mod
COPY deploy-docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN a2enmod headers

#Copy php.ini
COPY deploy-docker/php.ini /usr/local/etc/php/php.ini

#add run.sh
#RUN chmod +x deploy-docker/run.sh

#Add Custom EntryPoint
#COPY deploy-docker/docker-php-entrypoint /usr/local/bin/docker-php-entrypoint
#RUN chmod +x /usr/local/bin/docker-php-entrypoint

RUN bash -c 'mkdir -p /var/www/html/storage/{logs,framework/sessions,framework/views,framework/cache,data}'

RUN chown -R www-data:www-data /var/www/html/
RUN chmod 755 /var/www/html/storage/ -R

CMD ["apache2-foreground"]
