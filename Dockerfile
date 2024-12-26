FROM ubuntu:24.04

RUN apt-get update && apt-get install -y \
    software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && apt-get install -y \
    python3.6 \
    supervisor \
    php8.2 \
    php8.2-cli \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-xml \
    php8.2-mbstring \
    php8.2-curl \
    php8.2-zip \
    php8.2-gd \
    php8.2-bcmath \
    php8.2-intl \
    unzip \
    git \
    && apt install composer -y

WORKDIR /home/webex/Museum/

COPY . /home/webex/Museum/

RUN composer install

RUN php artisan key:generate

EXPOSE 8000

#RUN php artisan migrate:fresh --seed

#CMD ["php artisan serve"]
