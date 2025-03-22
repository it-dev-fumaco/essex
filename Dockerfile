# Use PHP with Apache as the base image
FROM php:7.4-apache as web

# Install Additional System Dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    unixodbc-dev \
    libpq-dev \
    libldap2-dev \
    wget \
    build-essential \
    libtool \
    autoconf \
    mdbtools \
    unixodbc \
    odbc-mdbtools

# Install Microsoft ODBC Driver for SQL Server
RUN curl https://packages.microsoft.com/keys/microsoft.asc | tee /etc/apt/trusted.gpg.d/microsoft.asc \
    && curl https://packages.microsoft.com/config/debian/11/prod.list | tee /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
    && ACCEPT_EULA=Y apt-get install -y mssql-tools18 \
    && echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc \
    && /bin/bash -c "source ~/.bashrc" \
    && apt-get install -y unixodbc-dev

# Extract PHP source and install odbc extension
RUN set -x \
    && docker-php-source extract \
    && cd /usr/src/php/ext/odbc \
    && phpize \
    && sed -ri 's@^ *test +"\$PHP_.*" *= *"no" *&& *PHP_.*=yes *$@#&@g' configure \
    && ./configure --with-unixODBC=shared,/usr \
    && docker-php-ext-install odbc \
    && docker-php-source delete

# Configure and install ldap extension
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap

# Install other PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Enable PHP ODBC extensions
RUN docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr \
    && docker-php-ext-install pdo_odbc \
    && docker-php-ext-enable pdo_odbc

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set DocumentRoot to Laravel public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy the application code
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy ODBC configuration files
COPY odbcinst.ini /etc/odbcinst.ini
COPY odbc.ini /etc/odbc.ini

# Copy custom php.ini with extensions enabled
COPY ./docker/php.ini /usr/local/etc/php/conf.d/php.ini

# Trust the Git directory
RUN git config --global --add safe.directory /var/www/html
