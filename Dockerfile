FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    curl \
    libcurl4-gnutls-dev \
    libxml2-dev \
    libssl-dev \
    libmagickwand-dev \
    libgmp-dev \
    libicu-dev \
    libmemcached-dev \
    libsqlite3-dev \
    libxslt-dev \
    libz-dev \
    libpq-dev \
    libonig-dev \
    libpcre3-dev \
    libreadline-dev \
    libedit-dev \
    zlib1g-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) iconv pdo mysqli pdo_mysql zip curl xml gd intl exif

# Install imagick extension
RUN pecl install imagick && docker-php-ext-enable imagick
RUN apt clean && rm -rf /var/lib/apt/lists/*h gd


#Composer Packages
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# NPM Packages
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs