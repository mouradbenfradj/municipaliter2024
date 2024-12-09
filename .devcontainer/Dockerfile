FROM mcr.microsoft.com/devcontainers/php:1-8-bullseye

# Installer le client MariaDB et les dépendances nécessaires
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y --no-install-recommends \
        mariadb-client \
        libicu-dev \
        libxml2-dev \
        libzip-dev \
        zlib1g-dev \
        libonig-dev \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
        intl \
        xml \
        mbstring \
        opcache \
        zip

# Vérifier si Xdebug est déjà installé et l'installer
RUN if ! php -m | grep -q xdebug; then \
    pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    fi

# Copier le fichier de configuration xdebug.ini à l'emplacement approprié
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Exposer le port pour Xdebug
EXPOSE 9000