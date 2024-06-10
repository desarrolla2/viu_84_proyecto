FROM docker_base_image

# Configure timezone
RUN ln -fs /usr/share/zoneinfo/Europe/Madrid /etc/localtime

# Install php
RUN add-apt-repository ppa:ondrej/php
RUN mkdir /run/php

RUN apt-get update && apt-get upgrade -y

RUN apt-get install -y \
    php8.2 \
    php8.2-apcu \
    php8.2-curl \
    php8.2-dom \
    php8.2-fpm \
    php8.2-mbstring \
    php8.2-xdebug \
    php8.2-xml \
    php8.2-zip \
    poppler-utils

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Add some configuration
ADD filesystem/ /

CMD ["php-fpm8.2", "-F"]
